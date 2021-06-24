<?php

class dbh extends db {
  private static $has_opt;
  public static $query;

  /*
  * check if required hasOpt is true in order to make db connection and create table
  */
  public static function handle_connect() {
    self::can_connect();

    if (self::$has_opt == true) {
      parent::connect();
      self::create_table();

      echo sprintf("creating table `users` in database: %s \n", parent::$database);
    } else return;
   }

  private static function can_connect() {
    if (
      get_opt::$args->hasOpt('host') &&
      get_opt::$args->hasOpt('user') &&
      get_opt::$args->hasOpt('password') &&
      get_opt::$args->hasOpt('database') &&
      get_opt::$args->hasOpt('create_table')
      ) self::$has_opt = true;
  }

  public function create_table() {
    $query = parent::$con->multi_query(self::$query) or die(parent::$con->error . "\n");
  }
}
