<?php

	$tests_directory = $module->private("tests");
	$asserts->expect_equal(count(iterator_to_array($tests_directory->get_files(true))), 1);
	$asserts->expect_equal(count(iterator_to_array($tests_directory->get_files(false))), 0);
	$asserts->expect_equal($tests_directory->exists(), true);

	// Carrega arquivos de teste.
	$asserts->expect_equal($module->public("tests/hello.php")->exists(), true);
	$asserts->expect_equal(file_get_contents($module->public("tests/hello.php")->get_url(true)), "Nope!");
	$asserts->expect_equal(file_get_contents($module->public("tests/hello.php?engine")->get_url(true)), "Hello World!");
