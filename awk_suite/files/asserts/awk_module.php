<?php

	$asserts->expect_equal(is_bool($module->is_localhost()), true);
	$asserts->expect_equal(is_bool($module->is_development()), true);

	$asserts->expect_equal(get_class($module->libraries), "AwkLibraryFeature");

	$asserts->expect_equal(AwkModule::exists($module->get_name()), true);

	// Identificador.
	$asserts->expect_equal(get_class($module->identify("library@awk_suite->tests/valid_unique")), "AwkLibrary");
	$asserts->expect_equal(get_class($module->identify("library@tests/valid_unique")), "AwkLibrary");

	$asserts->expect_equal(count($module->identify("library@tests/valid_unique::test")), 2);
	$asserts->expect_equal(count($module->identify("library@tests/valid_unique", null, null, null, true)), 4);
