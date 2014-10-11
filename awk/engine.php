<?php

    // Carrega a classe principal do motor.
    require_once "classes/Awk.php";

    // Inicia o autoloader do composer, se houver.
    $composer_autoloader = __DIR__ . "/../vendor/autoload.php";
    if(is_readable($composer_autoloader)) {
        require $composer_autoloader;
    }

    // Inicia o processo.
    Awk::register();
    Awk::init();
