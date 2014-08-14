<?php

	/** DATABASE */
	// Configuração do database para testes.
	// @type array;
	$settings->database_configure = [];

	/** COVERAGE */
	// Determina o diretório de saída do Coverage.
	// @type string;
	$settings->coverage_output_dir = __DIR__ . "/publics/coverage";

	// Determina se o Code Coverage estará ativado.
	// @type boolean;
	$settings->coverage_enabled = is_dir($settings->coverage_output_dir)
		&& is_writable($settings->coverage_output_dir)
		&& class_exists("PHP_CodeCoverage");

	/** TESTS */
	// Define uma variável global.
	$module->globals->set("test", "ok");
