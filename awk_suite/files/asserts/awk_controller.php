<?php

	// Inicia um controller.
	$controller_instance = $module->controller("tests/valid");
	$asserts->expect_equal($controller_instance instanceof AwkBase, true);

	// Code Coverage.
	$controller_instance = $module->controller("tests/valid");
	$asserts->expect_equal($controller_instance instanceof AwkBase, true);
