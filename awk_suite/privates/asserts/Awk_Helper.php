<?php

	// Responsável por testes no helper type, vastamente usando na suite.
	$type_helper = $module->helper("type");

	// Verifica os retornos por tipo.
	$asserts->expect_equal($type_helper->call("normalize", true), "bool(true)");
	$asserts->expect_equal($type_helper->call("normalize", false), "bool(false)");
	$asserts->expect_equal($type_helper->call("normalize", -1), "int(-1)");
	$asserts->expect_equal($type_helper->call("normalize", 0), "int(0)");
	$asserts->expect_equal($type_helper->call("normalize", 1), "int(1)");
	$asserts->expect_equal($type_helper->call("normalize", null), "null");
	$asserts->expect_equal($type_helper->call("normalize", 1.5), "float(1.5)");
	$asserts->expect_equal($type_helper->call("normalize", 1.0), "float(1)");
	$asserts->expect_equal($type_helper->call("normalize", 0.5), "float(0.5)");
	$asserts->expect_equal($type_helper->call("normalize", -0.0), "float(0)");
	$asserts->expect_equal($type_helper->call("normalize", "hello"), "string(\"hello\")");
	$asserts->expect_equal($type_helper->call("normalize", ""), "string(empty)");
	$asserts->expect_equal($type_helper->call("normalize", [1, 2, 3]), "array([1,2,3])");
	$asserts->expect_equal($type_helper->call("normalize", []), "array(empty)");
	$asserts->expect_equal($type_helper->call("normalize", new mysqli), "object(mysqli)");
	$asserts->expect_equal($type_helper->call("normalize", acos(8)), "float(nan)");
	$asserts->expect_equal($type_helper->call("normalize", stream_context_create()), "resource(stream-context)");

	// Exceções.
	$asserts->expect_exception(function() use($module) {
		$module->helper("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui o helper \"unexistent\".");
