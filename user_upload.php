<?php
  // declare commandline options
  $short_options = "h:u:p:";
  $long_options = ["file::", "create_table::", "dry_run::", "help::"];

  $options = getopt($short_options, $long_options);

  $mysqli = new mysqli( 'localhost', 'user', 'password', 'catalyst_exe')
    or die($mysqli->error . "\n");

  // create/rebuild users table
  function create_user_table($db) {
    $user_table_sql = "
      DROP TABLE IF EXISTS users;
      CREATE TABLE users (
        name VARCHAR(150),
        surname VARCHAR(150),
        email VARCHAR(150) UNIQUE
      )";

    $user_table_res = $db->multi_query($user_table_sql)
      or die($db->error . "\n");

    $db->close();
  }

  if (array_key_exists('create_table', $options)) {
    create_user_table($mysqli);

    echo "Creating new 'user' table in database.. \n";
  }
?>
