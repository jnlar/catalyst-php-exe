<?php
  class DbController {
    private $host;
    private $user;
    private $password;
    private $database;
    private $user_table_sql = "
      DROP TABLE IF EXISTS users;
      CREATE TABLE users (
        name VARCHAR(150),
        surname VARCHAR(150),
        email VARCHAR(150) UNIQUE
      )";

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
        $this->database) or die($con->error . "\n");
    }

    public function create_user_table() {
      $this->con->multi_query($this->user_table_sql)
        or die($con->error . "\n");

      echo sprintf("Creating a new `%s` table in %s \n", $this->user, $this->database);

      $this->con->close();
    }
  }
?>
