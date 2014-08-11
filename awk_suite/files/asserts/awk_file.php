<?php

	$tests_directory = $module->file("tests");
	$asserts->expect_equal(count(iterator_to_array($tests_directory->get_files(true))), 1);
	$asserts->expect_equal(count(iterator_to_array($tests_directory->get_files(false))), 0);
	$asserts->expect_equal($tests_directory->exists(), true);
