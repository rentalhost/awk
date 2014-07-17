<?php

	// Define um "null" (valor explicitamente nulo).
	// Pode ser usado para determinar o fim de uma rota.
	$type->set_validate(function($value) {
		return $value === null;
	});
