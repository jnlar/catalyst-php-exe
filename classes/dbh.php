<?php
class dbh extends db {
  private static $create_opt;
  public static $db_opt;
  public static $query;
  public static $table;

  public static function handle_create() {
    if (self::$db_opt) {
      parent::connect();
      self::create_table();

      echo get_opt::$cli->green(sprintf("Creating table `%s` in database: %s \n", self::$table, parent::$database));
    } else return;
   }

  private static function create_table() {
    parent::$con->multi_query(self::$query) or die(parent::$con->error . "\n");
  }

  private static function check_table_exists() {
    $query = "SELECT count(*) FROM information_schema.TABLES WHERE (TABLE_SCHEMA = ?) AND (TABLE_NAME = ?)";

    $statement = parent::$con->prepare($query);
    $statement->bind_param('ss', parent::$database, self::$table);
    $statement->execute();
    $res = $statement->get_result();
    $row = $res->fetch_assoc();

    if ($row['count(*)'] == 0) {
      throw new \Exception();
    }
  }

  private static function exception_dbh_opt() {
    if (!self::$db_opt) {
      throw new \Exception();
    }
  }

  private static function check_dbh_opt() {
    try {
      self::exception_dbh_opt();
    } catch (\Exception $e) {
      get_opt::print_error("ERROR: Run --file with either --dry_run or database credential flags\n\n");
    }
  }

  public static function handle_insert() {

    self::check_dbh_opt();
    parent::connect();

    try {
      self::check_table_exists();
    } catch (\Exception $e) {
      get_opt::print_error("ERROR: " . self::$table . " table doesn't exist, run --create_table first\n\n");
    }

    $query = "INSERT INTO " . self::$table . " (name, surname, email) VALUES (?, ?, ?)";

    $statement = parent::$con->prepare($query);

    foreach (parse::$user_data as $user) {
      if (!parse::check_valid_email($user)) {
        $statement->bind_param('sss', $user['name'], $user['surname'], $user['email']);
        $statement->execute();

        echo get_opt::$cli->green(sprintf("Inserting: %s, %s, %s (OK) into table: %s\n",
          $user['name'], $user['surname'], $user['email'], self::$table));
      } else {
        echo get_opt::$cli->red(sprintf("Not inserting: %s, %s, %s (INVALID FORMAT)\n",
          $user['name'], $user['surname'], $user['email']));
      }
    }

    parent::$con->close();
  }
}
