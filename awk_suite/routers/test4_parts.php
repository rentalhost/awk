<?php

    // Argumento simples.
    $router->add_route("args/simple", function() {
        echo "simple";
    });

    // Argumentos opcionais.
    $router->add_route("args/:{int}?/:{float}?/:{string}?", function($driver, $int, $float, $string) {
        echo "{$int},{$float},{$string}";
    });

    // Argumentos com repetição simples (+).
    $router->add_route("repeat/simple-one/:{int}+/abc", function($driver, $ints) {
        echo join(",", $ints);
    });

    // Argumentos com repetição simples (*).
    $router->add_route("repeat/simple-zero/:{int}*/abc", function($driver, $ints) {
        echo join(",", $ints);
    });

    // Argumentos com repetição exata.
    $router->add_route("repeat/exactly/:{int}{3}/abc", function($driver, $ints) {
        echo join(",", $ints);
    });

    // Argumentos com repetição mínima.
    $router->add_route("repeat/min/:{int}{3,}/abc", function($driver, $ints) {
        echo join(",", $ints);
    });

    // Argumentos com repetição mínima opcional.
    $router->add_route("repeat/min-optional/:{int}{3,}?/:{string}", function($driver, $ints, $string) {
        echo join(",", $ints) . ",{$string}";
    });

    // Argumentos com repetição máxima.
    $router->add_route("repeat/max/:{int}{,3}/abc?", function($driver, $ints) {
        echo join(",", $ints);
    });

    // Argumentos com repetição no alcance.
    $router->add_route("repeat/ranged/:{int}{2,3}/abc?", function($driver, $ints) {
        echo join(",", $ints);
    });

    // Argumento com captura de nome.
    $router->add_route("capture/:{string @name}", function($driver) {
        echo $driver->get_attr("name");
    });

    // Falha.
    $router->add_tunnel(function() {
        echo "fail";
    });
