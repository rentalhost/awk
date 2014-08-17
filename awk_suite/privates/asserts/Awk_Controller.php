<?php

	// Inicia um controller.
	$controller_instance = $module->controller("tests/valid");
	$asserts->expect_equal($controller_instance instanceof Awk_Base, true);

	// Code Coverage.
	$controller_instance = $module->controller("tests/valid");
	$asserts->expect_equal($controller_instance instanceof Awk_Base, true);
