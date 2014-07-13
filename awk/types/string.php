<?php

	// Define uma "string".
	// Qualquer informação escalar é uma string válida.
	$type->set_validate(function($value) {
		return is_scalar($value);
	});

	// Define a transformação da string.
	$type->set_transform(function($value) {
		return (string) $value;
	});
