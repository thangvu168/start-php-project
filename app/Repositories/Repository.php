<?php

class Repository
{
  protected string $table;
  protected string $primaryKey = 'id';
  protected array $allowedColumns = [];

  public function __construct(protected mysqli $db) {}

  // Kiểm tra cột có hợp lệ không, nếu allowedColumns rỗng thì cho phép tất cả, nếu không thì phải nằm trong allowedColumns
  private function validateColumn(string $column): void
  {
    if (!empty($this->allowedColumns) && !in_array($column, $this->allowedColumns, true)) {
      throw new InvalidArgumentException("Cột '$column' không hợp lệ.");
    }
  }

  // Kiểm tra sort, chỉ cho phép 'ASC' hoặc 'DESC', mặc định là 'ASC'
  private function validateDirection(string $dir): string
  {
    return $dir === 'DESC' ? 'DESC' : 'ASC';
  }

  public function getById(int $id): ?array
  {
    $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc() ?: null;
  }

  /**
   * Example:
   * $filters = [
   *   ['column' => 'status', 'op' => '=', 'value' => 'active'],
   *   ['column' => 'created_at', 'op' => '>', 'value' => '2024-01-01'],
   *   ['column' => 'id', 'op' => 'IN', 'value' => [1, 2, 3]],
   *   ['column' => 'price', 'op' => 'BETWEEN', 'value' => [10, 100]],
   * ];
   * $sort = ['column' => 'created_at', 'direction' => 'DESC'];
   * $limit = 10;
   * $offset = 20;
   *
   * SQL = "SELECT * FROM table WHERE status = 'active' AND created_at > '2024-01-01' AND id IN (1, 2, 3) AND price BETWEEN 10 AND 100 ORDER BY created_at DESC LIMIT 10 OFFSET 20"
   */
  public function getAll(
    array $filters = [],
    ?array $sort = null,
    ?int $limit = null,
    ?int $offset = null
  ): array {
    $sql = "SELECT * FROM {$this->table}";
    $params = [];
    $types = [];

    if (!empty($filters)) {
      $conditions = [];

      foreach ($filters as $filter) {
        $column = $filter['column'] ?? null;
        $op = strtoupper($filter['op'] ?? '=');
        $value  = $filter['value'] ?? null;

        if (!$column) {
          continue;
        }

        $this->validateColumn($column);

        switch ($op) {
          case '=':
          case '>':
          case '<':
          case '>=':
          case '<=':
          case '!=':
            $conditions[] = "$column $op ?";
            $params[] = $value;
            $types[] = $this->detectType($value);
            break;

          case 'LIKE':
            $conditions[] = "$column LIKE ?";
            $params[] = $value;
            $types[] = 's';
            break;

          case 'IN':
            // Ex: ['column' => 'id', 'op' => 'IN', 'value' => [1, 2, 3]]
            if (is_array($value) && count($value)) {
              $placeholders = implode(',', array_fill(0, count($value), '?'));
              $conditions[] = "$column IN ($placeholders)";
              foreach ($value as $v) {
                $params[] = $v;
                $types[] = $this->detectType($v);
              }
            }
            break;

          case 'BETWEEN':
            if (is_array($value) && count($value) === 2) {
              $conditions[] = "$column BETWEEN ? AND ?";
              $params[] = $value[0];
              $params[] = $value[1];
              $types[] = $this->detectType($value[0]);
              $types[] = $this->detectType($value[1]);
            }
            break;
        }
      }

      if ($conditions) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
      }
    }

    if (!empty($sort)) {
      $column = $sort['column'] ?? null;
      $dir    = $this->validateDirection(strtoupper($sort['dir'] ?? 'ASC'));

      if ($column) {
        $this->validateColumn($column);
        $sql .= " ORDER BY $column $dir";
      }
    }

    if ($limit !== null) {
      $sql .= " LIMIT ?";
      $params[] = $limit;
      $types[] = 'i';

      if ($offset !== null) {
        $sql .= " OFFSET ?";
        $params[] = $offset;
        $types[] = 'i';
      }
    }

    $stmt = $this->db->prepare($sql);

    if (!empty($params)) {
      $stmt->bind_param(implode('', $types), ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
  }


  public function create(array $data): int
  {
    foreach (array_keys($data) as $col) {
      $this->validateColumn($col);
    }

    // implode: join array elements into a string with a separator
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
    $stmt = $this->db->prepare($sql);

    $types = implode('', array_map(fn($v) => $this->detectType($v), array_values($data)));
    $stmt->bind_param($types, ...array_values($data));
    $stmt->execute();

    return $stmt->insert_id;
  }

  public function update(int $id, array $data): bool
  {
    foreach (array_keys($data) as $col) {
      $this->validateColumn($col);
    }

    $setClause = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
    $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
    $stmt = $this->db->prepare($sql);

    $values = array_values($data);
    $types = implode('', array_map(fn($v) => $this->detectType($v), $values)) . 'i';
    $params = array_merge($values, [$id]);
    $stmt->bind_param($types, ...$params);
    return $stmt->execute();
  }

  public function delete(int $id): bool
  {
    $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('i', $id);
    return $stmt->execute();
  }

  public function execute(string $sql, array $params = []): array
  {
    $stmt = $this->db->prepare($sql);
    if (!empty($params)) {
      $types = str_repeat('s', count($params));
      $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();

    // Nếu câu lệnh là SELECT, trả về kết quả, các câu lệnh trả lại thông tin qua array
    // affected_rows: số dòng bị ảnh hưởng, insert_id: id của bản ghi mới tạo (nếu có)
    $meta = $stmt->result_metadata();

    // If there is no metadata, the statement did not produce a result set
    if ($meta === false) {
      return [
        'affected_rows' => $stmt->affected_rows,
        'insert_id' => $stmt->insert_id,
      ];
    }

    $fields = $meta->fetch_fields();
    if (!is_array($fields) || count($fields) === 0) {
      return [
        'affected_rows' => $stmt->affected_rows,
        'insert_id' => $stmt->insert_id,
      ];
    }

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  // Kiểm tra type của giá trị để bind_param đúng loại
  private function detectType($value): string
  {
    return match (true) {
      is_int($value) => 'i',
      is_float($value) => 'd',
      default => 's',
    };
  }
}
