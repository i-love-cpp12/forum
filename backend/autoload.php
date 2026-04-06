<?php
spl_autoload_register(function ($className) {
    $file = __DIR__ . "/" . $className . ".php";
    // echo $file . "<br>";
    if(file_exists($file))
        require($file);
});