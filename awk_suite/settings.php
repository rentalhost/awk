<?php

	/** COVERAGE */
	// Determina se o Code Coverage estará ativado.
	//@type boolean;
	$settings->coverage_enabled = class_exists("PHP_CodeCoverage");

	// Determina o diretório de saída do Coverage.
	//@type string;
	$settings->coverage_output_dir = "/tmp/code-coverage-report";
