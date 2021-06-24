<?php
require_once 'vendor/autoload.php';

use Garden\Cli\Cli;

class get_opt {
  public static $cli;
  public static $args;

  public function __construct() {
    self::$cli = new Cli;
    $this->init_opt();
    @self::$args = self::$cli->parse($argv, true);
  }

  private function init_opt() {
    self::$cli->description('Extensible CLI script that parses CSV data and inserts into a MySQL database')
    	->opt('file', '[csv file name] - This is the name of the CSV file to be parsed', false)
    	->opt('create_table', 'This will cause the MySQL users table to be built (no further action will be taken)', false)
    	->opt('dry_run', 'To be used with the --file directive, this option will parse the CSV file but not insert into the database', false)
    	->opt('user:u', 'MySQL username', false)
    	->opt('password:p', 'MySQL password', false)
    	->opt('host:h', 'MySQL host', false)
    	->opt('database:d', 'MySQL database', false);
  }
}
