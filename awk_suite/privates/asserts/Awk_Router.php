<?php

	$asserts->expect_equal(get_class($module->router("tests/index")), "Awk_Router");
	$asserts->expect_equal(count($module->router("tests/index")->get_routes()) > 0, true);
	$asserts->expect_equal(strpos($module->router("tests/index")->file_path(), "suite/run") !== false, true);
	$asserts->expect_equal(strpos($module->router("tests/index")->get_url(), "suite/run") !== false, true);

	$asserts->expect_equal($module->routers->exists("tests/index"), true);

	// Exceções.
	$asserts->expect_exception(function() use($module) {
		$module->router("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui o roteador \"unexistent\".");
