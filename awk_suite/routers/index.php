<?php

	// Define a rota de raíz (apresentação da home).
	$router->add_root("controller@suite::home_page");

	// Inicia o processo de execução dos asserts.
	$router->add_route("run/[awk->string @options]*", function($driver, $options) use($module) {
		return $module->controller("suite")->home_run($options);
	});
