<?php

spl_autoload_register(function($name) {
    $path = preg_replace('/\\\/', '/', dirname(__DIR__) . "/$name.php");

    include $path;
});
