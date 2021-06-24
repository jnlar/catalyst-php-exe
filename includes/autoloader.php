<?php
spl_autoload_register('auto_loader');

function auto_loader($class_name) {
  $path = "classes/";
  $extension = ".php";
  $full_path = $path . $class_name . $extension;

  if (!file_exists($full_path)) {
    return false;
  }

  include_once $full_path;
}
?>
