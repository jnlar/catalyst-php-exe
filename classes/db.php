<?php

/*
* Database class, contains database credential values,
* credential getting and connection methods
*/
class db {
  protected static $host;
  protected static $user;
  protected static $password;
  protected static $database;

  public static $query;
  public static $con;

  /*
  * Set database credentials
  * @opt::handle_db_opt
  *
  * @param array[]
  */
  public static function get_db_cred(array $args) {
    self::$host = $args[0];
    self::$user = $args[1];
    self::$password = $args[2];
    self::$database = $args[3];
  }

  /*
  * Attempt database connection with db credentials
  */
  public static function connect() {
    // NOTE: we are spitting out default mysqli errors still
    self::$con = new mysqli(
      self::$host,
      self::$user,
      self::$password,
      self::$database,
    ) or die(self::$con->error . '\n');
  }
}
