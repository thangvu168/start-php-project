<?php

class Repository
{
  protected string $table;
  protected string $primaryKey = 'id';

  public function __construct(protected mysqli $db) {}

  public function getById(int $id): ?array
  {
    $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc() ?: null;
  }

  public function getAll(): array
  {
    $sql = "SELECT * FROM {$this->table}";
    $result = $this->db->query($sql);
    // Flag MYSQLI_ASSOC: return an associative array where the keys are the column names
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function create(array $data): int
  {
    // implode: join array elements into a string with a separator
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
    $stmt = $this->db->prepare($sql);

    // Using s to dynamically bind parameters based on the number of data elements
    $types = str_repeat('s', count($data));
    $stmt->bind_param($types, ...array_values($data));
    $stmt->execute();

    return $stmt->insert_id;
  }

  public function update(int $id, array $data): bool
  {
    $setClause = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
    $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
    $stmt = $this->db->prepare($sql);

    $types = str_repeat('s', count($data)) . 'i';
    $stmt->bind_param($types, ...array_values($data), $id);
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
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }
}
