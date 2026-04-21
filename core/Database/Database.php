<?php

class Database
{
  private static ?mysqli $connection = null;

  // Singleton
  public static function getConnection(): mysqli
  {
    if (self::$connection === null) {
      self::$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

      if (self::$connection->connect_error) {
        die("Connection failed: " . self::$connection->connect_error);
      }
    }

    return self::$connection;
  }
}
