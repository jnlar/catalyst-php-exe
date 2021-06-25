<?php
require 'includes/autoloader.php';

$get_opt = new get_opt();
get_opt::$args = get_opt::$cli->parse($argv, true);

$parse = new parse();

$table = dbh::$table = "users";
dbh::$query = "
	DROP TABLE IF EXISTS $table;
	CREATE TABLE $table (
	name VARCHAR(150),
	surname VARCHAR(150),
	email VARCHAR(150) UNIQUE);
";

if (count($argv) == 1) {
	get_opt::$cli->writeHelp();
}

$get_opt->bind_opt();
