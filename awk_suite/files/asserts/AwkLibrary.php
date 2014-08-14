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
	$asserts->expect_equal(get_class($class_instance->get_module()), "AwkModule");
