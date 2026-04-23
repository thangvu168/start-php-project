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
        die("Connection failed: " . self::$connection->connect_error);
      }
    }

    return self::$connection;
  }
}
