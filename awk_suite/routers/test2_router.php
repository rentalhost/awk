<?php

    // Adiciona uma rota com preserva de URL.
    $router->add_route("preserve/simple_route", function() {
        echo "simple_route_preserved";
    });

    // Adiciona uma outra rota.
    $router->add_route("simple_other", function() {
        echo "simple_other";
    });
