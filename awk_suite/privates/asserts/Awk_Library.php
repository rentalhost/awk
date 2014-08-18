<?php

	// Code Coverage.
	$library_instance = $module->library("tests/valid_autoinit");
	$library_instance->unique();

	// Verifica a instância única.
	$class_instance = $library_instance->unique();
	$asserts->expect_equal($class_instance->init_number, 1);

	// Cria uma nova instância.
	$class_instance = $library_instance->create();
	$asserts->expect_equal($class_instance->init_number, 2);

	// Unique devem se manter iguais entre as execuções.
	$class_instance = $library_instance->unique();
	$asserts->expect_equal($class_instance->init_number, 1);

	// Code Coverage.
	$library_instance = $module->library("tests/valid_unique");
	$class_instance = $library_instance->unique();
	$asserts->expect_equal(get_class($class_instance->get_module()), "Awk_Module");

	// Exceções.
	$asserts->expect_exception(function() use($module) {
		$module->library("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui a library \"unexistent\".");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/unregistered");
	}, "Awk_Error_Exception", "A library \"tests/unregistered\" do módulo \"awk_suite\" não efetuou o registro de classe.");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid1");
	}, "Awk_Error_Exception", "A library \"tests/invalid1\" do módulo \"awk_suite\" registrou uma classe inexistente (\"Unexistent_Class\").");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid2")->unique();
	}, "Awk_Error_Exception", "O método \"library_unique\" da library \"AwkSuite_Invalid2_Test\" do módulo \"awk_suite\" não retornou " .
		"uma instância da classe \"AwkSuite_Invalid2_Test\", ao invés disso, retornou \"stdClass\".");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid3")->unique();
	}, "Awk_Error_Exception", "A instância única da library \"AwkSuite_Invalid3_Test\" do módulo \"awk_suite\" não pôde ser criada pois " .
		"seu construtor requer parâmetros. Considere definir o método \"library_unique\".");
