<?php
require 'includes/autoloader.php';

$opt = new opt();
// NOTE: can this be a public function? we instantiate the opt object
opt::$args = opt::$cli->parse($argv, true);

$parse = new parse();

/*
* declare the name of the table and table creation quuery here
*/
$table = dbh::$table = "users";
dbh::$query = "
	DROP TABLE IF EXISTS $table;
	CREATE TABLE $table (
	name VARCHAR(150),
	surname VARCHAR(150),
	email VARCHAR(150) UNIQUE);
";

// NOTE: dirty but works
if (count($argv) == 1) {
	opt::$cli->writeHelp();
}

$opt->bind_opt();
