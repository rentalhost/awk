<?php

    /**
     * Define os validadores e transformadores dos tipos padrões suportados pelo framework.
     */

    // Tipo boolean.
    $module->types->create("bool",    "Awk_Type_Helper::boolean_validate", "Awk_Type_Helper::boolean_transform");
    $module->types->create("boolean", "Awk_Type_Helper::boolean_validate", "Awk_Type_Helper::boolean_transform");

    // Tipo float.
    $module->types->create("float",   "Awk_Type_Helper::float_validate",   "Awk_Type_Helper::float_transform");
    $module->types->create("double",  "Awk_Type_Helper::float_validate",   "Awk_Type_Helper::float_transform");

    // Tipo integer.
    $module->types->create("int",     "Awk_Type_Helper::int_validate",     "Awk_Type_Helper::int_transform");
    $module->types->create("integer", "Awk_Type_Helper::int_validate",     "Awk_Type_Helper::int_transform");

    // Outros tipos.
    $module->types->create("string",  "Awk_Type_Helper::string_validate",  "Awk_Type_Helper::string_transform");
    $module->types->create("empty",   "Awk_Type_Helper::empty_validate",   "Awk_Type_Helper::empty_transform");
    $module->types->create("null",    "Awk_Type_Helper::null_validate",    "Awk_Type_Helper::null_transform");
