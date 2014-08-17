<?php

	$view_instance = $module->view("tests/hello", null, true);
	$asserts->expect_equal($view_instance->was_printed(), false);
	$asserts->expect_equal($view_instance->get_return(), 1);
	$asserts->expect_equal($view_instance->get_contents(), "Hello World!");
	$asserts->expect_equal((string) $view_instance, "Hello World!");
	$asserts->expect_equal($view_instance->exists(), true);
