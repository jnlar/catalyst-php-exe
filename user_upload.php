<?php
require 'includes/db_controller.inc.php';
require 'includes/opt.inc.php';
require 'includes/parse_file.inc.php';

$opt = new Opt_controller();
$db = new Db_controller($opt->options);
$file = new Parse_file('./users.csv');

$file->test();

Db_controller::$query = "
	DROP TABLE IF EXISTS users;
		CREATE TABLE users (
		name VARCHAR(150),
		surname VARCHAR(150),
		email VARCHAR(150) UNIQUE";

$opt->handle_create_table($db, Db_controller::$query);
?>
