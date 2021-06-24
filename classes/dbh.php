<?php
class dbh extends db {
  private static $create_opt;
  private static $db_opt;
  private static $table_created;
  public static $query;
  public static $table;

  /*
  * check if required hasOpt is true in order to make db connection
  */
  private static function check_for_db_cred() {
    // TODO: error handling if not all connection options are specified
    if (
      get_opt::$args->hasOpt('host') &&
      get_opt::$args->hasOpt('user') &&
      get_opt::$args->hasOpt('password') &&
      get_opt::$args->hasOpt('database')
      ) self::$db_opt = true;
  }

  public static function handle_create() {
    self::check_for_db_cred();

    if (self::$db_opt && get_opt::$args->hasOpt('create_table')) {
      parent::connect();
      self::create_table();

      self::$table_created = true;

      echo sprintf("Creating table `%s` in database: %s \n", self::$table, parent::$database);
    } else return;
   }

  private static function create_table() {
    parent::$con->multi_query(self::$query) or die(parent::$con->error . "\n");
  }

  private static function check_insert_opt() {
    // NOTE: can we handle cases where --create_table hasn't been run in this manner?
    if (!self::$db_opt && !self::$table_created) {
      throw new Exception("ERROR: ~~insert error message here~~");
    }
  }

  public static function handle_insert() {
    if (get_opt::$args->hasOpt('file') && self::$db_opt) {
      self::check_insert_opt();
      parent::connect();

      $query = "
      INSERT INTO " . self::$table . " (name, surname, email) VALUES (?, ?, ?)";

      //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

      $statement = parent::$con->prepare($query);

      // NOTE: Exception isn't being thrown for duplicate entries into database
      foreach (parse::$user_data as $user) {
        try {
          $statement->bind_param('sss', $user['name'], $user['surname'], $user['email']);
          $statement->execute();

          echo sprintf("inserting %s, %s, %s into table: %s\n",
            $user['name'], $user['surname'], $user['email'], self::$table);
        } catch (Exception $e) {
          echo sprintf("ERROR: %s \n", $e->getMessage());
        }
      }

      parent::$con->close();
    }
   }
}
