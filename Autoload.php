<?php

spl_autoload_register(function($class) {
    if (strpos($class, "Kendo\\") === 0) {
        $path = str_replace('Kendo', '', $class);

        $path = strtolower(__DIR__.str_replace('\\', '/', $path).'.php');

        require_once $path;
    }
});

?>
