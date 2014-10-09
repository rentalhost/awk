<?php

    // Valida uma "boolean".
    $type->set_validate(function($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) !== false;
    });

    // Define a transformação de int.
    $type->set_transform(function($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) !== false;
    });
