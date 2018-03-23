<?php

spl_autoload_register(function($name) {
    $filePath = preg_replace('/\\\/', '/', $name);

    include dirname(__DIR__) . "/$filePath.php";
});
