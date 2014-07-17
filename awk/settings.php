<?php

	/** FRAMEWORK */
	// Nome do framework.
	// @type string;
	$settings->framework_name = "awk";

	// Versão do framework.
	// @type array[int $max, int $min, int $path];
	$settings->framework_version = [0, 1, 0];

	/** ROUTES */
	// Define o módulo da rota inicial de páginas.
	// @type string identifier;
	$settings->router_default = "router@site->index";

	// Define o módulo da rota inicial de arquivos.
	// @type string identifier;
	$settings->router_file_default = "router@site->index.file";
