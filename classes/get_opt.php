<?php
require_once 'vendor/autoload.php';

use Garden\Cli\Cli;

class get_opt {
  public static $cli;
  public static $args;

  public function __construct() {
    self::$cli = new Cli;
    $this->init_opt();
  }

  private function init_opt() {
    self::$cli->description('CLI script that parses CSV data and inserts formatted CSV data into a MySQL database. Use to --help or -? for CLI options')
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

  private function handle_create_opt() {
    if (self::$args->hasOpt('create_table')) {
      return dbh::handle_create();
    }
  }

  private function handle_insert_opt() {
    if (self::$args->hasOpt('file')) {
      return dbh::handle_insert();
    }
  }

  private function handle_dry_run() {
    if (self::$args->hasOpt('file') && self::$args->hasOpt('dry_run')) {
      return parse::dry_run();
    }
  }
}
