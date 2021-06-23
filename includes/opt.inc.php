<?php
class Opt_controller {
  // declare commandline options
  public static $short_options = "h:u:p:d:";
  public static $long_options = ["file::", "create_table::", "dry_run::", "help::"];
  public $options;

  public function __construct() {
    $this->options = getopt(self::$short_options, self::$long_options);
  }

  public function handle_create_table($db, $query) {
		$this->opt_helper('create-table', $db->create_user_table($query));
  }

	private function opt_helper($opt, $callback) {
		if (array_key_exists($opt, $this->options)) {
			return $callback;
		}
	}
}
?>
