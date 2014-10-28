<?php

    // Define o método de validação.
    $type->set_validate(function() {
        return true;
    });

    // Define o método de tranformação.
    $type->set_transform(function() {
        return "ok";
    });
