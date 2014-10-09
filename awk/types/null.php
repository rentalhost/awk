<?php

    // Define um "null" (valor explicitamente nulo).
    // Para valores vazios (ou sem valor), utilizar "empty".
    // Pode ser usado para determinar o fim de uma rota.
    $type->set_validate("is_null");

    // Define a transformação de null.
    $type->set_transform(function() {
        return null;
    });
