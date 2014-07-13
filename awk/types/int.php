<?php

	// Define uma "int" (integer).
	// Uma integer é um número sem parte decimal.
	$type->set_validate(function($value) {
		return filter_var($value, FILTER_VALIDATE_INT) !== false
			&& !is_bool($value);
	});

	// Define a transformação de int.
	$type->set_transform(function($value) {
		return (int) $value;
	});
