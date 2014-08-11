<?php

	$asserts->expect_equal(is_bool($module->is_localhost()), true);
	$asserts->expect_equal(is_bool($module->is_development()), true);
	$asserts->expect_equal(get_class($module->libraries), "awk_library_feature");

	// Identificador.
	$asserts->expect_equal($module->identify("%invalid%"), false);

	$asserts->expect_equal(get_class($module->identify("library@awk_suite->tests/valid_unique")), "awk_library");
	$asserts->expect_equal(get_class($module->identify("library@tests/valid_unique")), "awk_library");

	$asserts->expect_equal(count($module->identify("library@tests/valid_unique::test")), 2);
	$asserts->expect_equal(count($module->identify("library@tests/valid_unique", null, null, null, true)), 4);
