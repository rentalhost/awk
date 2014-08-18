<?php

	// Inicia um controller.
	$controller_instance = $module->controller("tests/valid");
	$asserts->expect_equal($controller_instance instanceof Awk_Base, true);

	// Code Coverage.
	$controller_instance = $module->controller("tests/valid");
	$asserts->expect_equal($controller_instance instanceof Awk_Base, true);

	// Exceções.
	$asserts->expect_exception(function() use($module) {
		$module->controller("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui o controller \"unexistent\".");

	$asserts->expect_exception(function() use($module) {
		$module->controller("tests/unregistered");
	}, "Awk_Error_Exception", "O controller \"tests/unregistered\" do módulo \"awk_suite\" não efetuou o registro de classe.");

	$asserts->expect_exception(function() use($module) {
		$module->controller("tests/invalid1");
	}, "Awk_Error_Exception", "O controller \"tests/invalid1\" do módulo \"awk_suite\" registrou uma classe inexistente (\"Unexistent_Class\").");
