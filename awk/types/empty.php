<?php

    // Define um "empty" (sem valor, ou vazio).
    // Para valores explicitamente null, utilizar "null".
    $type->set_validate(function($value) {
        return empty($value);
    });

    // Define a transformação de empty.
    $type->set_transform(function() {
        return null;
    });
