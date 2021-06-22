<?php
class OptController {
  // declare commandline options
  public $short_options = "h:u:p:d:";
  public $long_options = ["file::", "create_table::", "dry_run::", "help::"];
  public $options;

  public function __construct() {
    $this->options = getopt($this->short_options, $this->long_options);
  }

  public function handle_create_table($db) {
    if (array_key_exists('create_table', $this->options)) {
      $db->create_user_table();
    }
  }

  public function handle_db_credential() {
  }
}
?>
