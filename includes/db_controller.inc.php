<?php
  class Db_controller {
    private $host;
    private $user;
    private $password;
    private $database;

		public static $query;
    public $con = null;

    // TODO: get -u -p -h values from cli options
    public function __construct($options) {
      if (
        array_key_exists('u', $options) &&
        array_key_exists('h', $options) &&
        array_key_exists('p', $options) &&
        array_key_exists('d', $options)
      ) {
        $this->host = $options['h'];
        $this->user = $options['u'];
        $this->password = $options['p'];
        $this->database = $options['d'];
      }

      $this->con_db();
    }

    private function con_db() {
      $this->con = new mysqli(
        $this->host,
        $this->user,
        $this->password,
        $this->database) or die($this->con->error . "\n");
    }

    public function create_user_table() {
			$this->con->multi_query(self::$query)
				or die($this->con->error . "\n");

      echo sprintf("Creating a new `%s` table in %s \n", $this->user, $this->database);

      $this->con->close();
    }
  }
?>
