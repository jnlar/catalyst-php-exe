<?php

require_once 'vendor/autoload.php';

use Garden\Cli\Cli;

/*
* class for handling cli options, method bindings print
* formatting to STDOUT
*/
class opt {
  public static $cli;
  public static $args;

  public function __construct() {
    self::$cli = new Cli;
    $this->init_opt();
  }


  /*
  * Initialise self::$cli options and description
  *
  * @return method/s
  */
  private function init_opt() {
    self::$cli->description("php user_upload.php [<options>]")
      ->opt('file', '[csv file name] - This is the name of the CSV file to be parsed', false)
    	->opt('create_table', 'This will cause the MySQL users table to be built (no further action will be taken)', false)
    	->opt('dry_run', 'To be used with the --file directive, this option will parse the CSV file but not insert into the database', false)
    	->opt('user:u', 'MySQL username', false)
    	->opt('password:p', 'MySQL password', false)
    	->opt('host:h', 'MySQL host', false)
    	->opt('database:d', 'MySQL database', false);
  }

  public function bind_opt() {
    $this->handle_db_opt();
    $this->handle_create_opt();
    $this->handle_insert_opt();
    $this->handle_dry_run();
  }

  /*
  * Generalised die for opt methods
  *
  * @param string $color
  * @param string $string
  * @return string
  */
  public static function error_die($color, $string) {
    self::print_color($color, $string);
    self::$cli->writeHelp();
    die;
  }

  /*
  * Generalised color printing method to STDOUT
  * and spreading sprintf values for format string
  *
  * @param string $color
  * @param string $format
  * @param string ...$values
  * @return string
  */
  public static function print_color($color, $format, ...$values) {
    echo self::$cli->$color(sprintf($format, ...$values));
  }

  /*
  * If we are given db credential flags, assign their values
  * as protected statics in db class
  *
  */
  private function handle_db_opt() {
    if (
      self::$args->hasOpt('host') &&
      self::$args->hasOpt('user') &&
      self::$args->hasOpt('password') &&
      self::$args->hasOpt('database')
    ) {
      db::get_db_cred([
      	self::$args->getOpt('host'),
      	self::$args->getOpt('user'),
      	self::$args->getOpt('password'),
      	self::$args->getOpt('database')
      ]);

      dbh::$db_opt = true;
    }
  }

  /*
  * Here we bind the appropriate methods to their cli option/s
  */
  private function handle_create_opt() {
    if (self::$args->hasOpt('create_table') && !self::$args->hasOpt('file')) {
      return dbh::handle_create();
    }
  }

  private function handle_insert_opt() {
    if (self::$args->hasOpt('file') && !self::$args->hasOpt('dry_run')) {
      return dbh::handle_insert();
    }
  }

  private function handle_dry_run() {
    if (self::$args->hasOpt('file') && self::$args->hasOpt('dry_run')) {
      return parse::dry_run();
    }
  }
}
