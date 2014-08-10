<?php

	// Define uma "float".
	// Int também são validados como float.
	$type->set_validate(function($value) {
		return filter_var($value, FILTER_VALIDATE_FLOAT) !== false
			&& !is_bool($value);
	});

	// Define a transformação de int.
	$type->set_transform(function($value) {
		return is_scalar($value)
			? (float) $value
			: 0.0;
	});
