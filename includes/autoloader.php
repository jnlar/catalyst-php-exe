<?php

/*
* Registers given function as __autoload implementation
*
* @param string
*/
spl_autoload_register('auto_loader');

/*
* Autoloads classes from /classes
*
* @param string
*/
function auto_loader($class_name) {
  $path = "classes/";
  $extension = ".php";
  $full_path = $path . $class_name . $extension;

  // produces a less verbose error message
  if (!file_exists($full_path)) {
    return false;
  }

  include_once $full_path;
}
