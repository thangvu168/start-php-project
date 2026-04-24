<?php

class Database
{
  private static ?mysqli $connection = null;

  // Singleton
  public static function getConnection(): mysqli
  {
    if (self::$connection === null) {
      $db = config('db');
      self::$connection = new mysqli($db['host'], $db['username'], $db['password'], $db['database']);

      if (self::$connection->connect_error) {
        error_log("DB connection failed: " . self::$connection->connect_error);
        die("Không thể kết nối cơ sở dữ liệu. Vui lòng thử lại sau.");
      }

      self::$connection->set_charset('utf8mb4');
    }

    return self::$connection;
  }
}
