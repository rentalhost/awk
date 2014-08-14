<?php

	$library_instance = $module->library("tests/valid_unique");
	$asserts->expect_equal(get_class($library_instance->get_module()), "AwkModule");
	$asserts->expect_equal(get_class($library_instance->get_parent()), "AwkLibraryFeature");
	$asserts->expect_equal(get_class($library_instance->get_parent()->get_module()), "AwkModule");
	$asserts->expect_equal($library_instance->get_name(), "tests/valid_unique");
	$asserts->expect_equal($library_instance->get_id(), "library@awk_suite->tests/valid_unique");
	$asserts->expect_equal(basename($library_instance->get_path()), "valid_unique.php");
	$asserts->expect_equal(basename($library_instance->get_path(false)), "valid_unique.php");
