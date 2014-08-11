<?php

	// Argumento simples.
	$router->add_route("args/simple", function() {
		echo "simple";
	});

	// Argumentos opcionais.
	$router->add_route("args/[awk->int]?/[awk->float]?/[awk->string]?", function($driver, $int, $float, $string) {
		echo "{$int},{$float},{$string}";
	});

	// Argumentos com repetição simples (+).
	$router->add_route("repeat/simple-one/[awk->int]+/abc", function($driver, $ints) {
		echo join(",", $ints);
	});

	// Argumentos com repetição simples (*).
	$router->add_route("repeat/simple-zero/[awk->int]*/abc", function($driver, $ints) {
		echo join(",", $ints);
	});

	// Argumentos com repetição exata.
	$router->add_route("repeat/exactly/[awk->int]{3}/abc", function($driver, $ints) {
		echo join(",", $ints);
	});

	// Argumentos com repetição mínima.
	$router->add_route("repeat/min/[awk->int]{3,}/abc", function($driver, $ints) {
		echo join(",", $ints);
	});

	// Argumentos com repetição mínima opcional.
	$router->add_route("repeat/min-optional/[awk->int]{3,}?/[awk->string]", function($driver, $ints, $string) {
		echo join(",", $ints) . ",{$string}";
	});

	// Argumentos com repetição máxima.
	$router->add_route("repeat/max/[awk->int]{,3}/abc?", function($driver, $ints) {
		echo join(",", $ints);
	});

	// Argumentos com repetição no alcance.
	$router->add_route("repeat/ranged/[awk->int]{2,3}/abc?", function($driver, $ints) {
		echo join(",", $ints);
	});

	// Falha.
	$router->add_passage(function() {
		echo "fail";
	});
