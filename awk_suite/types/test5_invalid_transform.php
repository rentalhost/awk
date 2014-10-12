<?php

    // Define um método de validação válido.
    $type->set_validate("is_string");

    // Define um método de transformação inválido.
    $type->set_transform("#invalid");
