<?php

    // Configuração do Database para testes.
    $settings->database_configuration = [ ];

    // Define algumas configurações para testes.
    $settings->test_value = 123;
    $settings->test_overwrited = "before";

    // Define uma variável global.
    $module->globals->set("test", "ok");
