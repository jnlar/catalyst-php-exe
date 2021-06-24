<?php
require 'includes/autoloader.php';

$get_opt = new get_opt();
$parse = new parse();

db::get_db_cred([
	get_opt::$args->getOpt('host'),
	get_opt::$args->getOpt('user'),
	get_opt::$args->getOpt('password'),
	get_opt::$args->getOpt('database')
]);

$table = dbh::$table = "users";
dbh::$query = "
	DROP TABLE IF EXISTS $table;
	CREATE TABLE $table (
	name VARCHAR(150),
	surname VARCHAR(150),
	email VARCHAR(150) UNIQUE);
";
dbh::handle_create();
dbh::handle_insert();
