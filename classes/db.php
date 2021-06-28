<?php

class db {
  protected static $host;
  protected static $user;
  protected static $password;
  protected static $database;

  public static $query;
  public static $con;

  public static function get_db_cred($args) {
    self::$host = $args[0];
    self::$user = $args[1];
    self::$password = $args[2];
    self::$database = $args[3];
  }

  public static function connect() {
    // NOTE: we are spitting out default mysqli errors still
    self::$con = new mysqli(
      self::$host,
      self::$user,
      self::$password,
      self::$database,
    ) or die(self::$con->error . '\n');

    return self::$con;
  }
}
