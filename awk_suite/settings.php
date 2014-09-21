<?php

	/** DATABASE */
	// Configuração do database para testes.
	// @type array;
	$settings->database_configuration = [ ];

	/** TESTS */
	// Define algumas configurações para testes.
	$settings->test_value = 123;
	$settings->test_overwrited = "before";

	// Define uma variável global.
	$module->globals->set("test", "ok");
