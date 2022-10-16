<?php

require_once BharatPHP_ROOT_PATH . '/vendor/autoload.php';

// spl_autoload_register(function ($class) {
//     $class = str_replace('BharatPHP\\', '', $class);
//     $class = str_replace('\\', '/', $class);
//     $class_namespace = explode('/', $class);
//     for ($i = 0; $i < (count($class_namespace) - 1); $i++) {
//         $class_namespace[$i] = ($class_namespace[$i]);
//     }
//     $class = implode('/', $class_namespace);
//     if (is_readable(BharatPHP_ROOT_PATH . '/' . $class . '.php')) {
//         require_once BharatPHP_ROOT_PATH . '/' . $class . '.php';
//     }
// });
