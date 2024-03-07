<?php

function autoload($class) {
  $boardBase = DIR_APP . DS;
  $class = $boardBase . 'Classes' . DS . str_replace('\\', DS, $class) . '.php';
  if (file_exists($class) && !is_dir($class)) {
    include $class;
  }
}

spl_autoload_register('autoload');