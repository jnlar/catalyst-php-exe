<?php
require 'includes/db_controller.inc.php';
require 'includes/opt.inc.php';

$opt = new OptController();
$db = new DbController($opt->options);

$opt->handle_create_table($db);
?>
