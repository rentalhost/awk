<?php

	// Inicia um controller.
	$controller_instance = $module->controller("tests/valid");
	$asserts->expect_equal($controller_instance instanceof awk_base, true);

	// Code Coverage.
	$controller_instance = $module->controller("tests/valid");
	$asserts->expect_equal($controller_instance instanceof awk_base, true);
