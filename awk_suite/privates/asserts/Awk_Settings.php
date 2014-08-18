<?php

	$module_settings = $module->settings();
	unset($module_settings->unknow_setting);
	$asserts->expect_equal(isset($module_settings->coverage_output_dir), true);

	$asserts->expect_equal(basename($module_settings->overwrite_path()), "settings.awk_suite.php");
	$asserts->expect_equal($module_settings->overwrite_exists(), true);

	// Define uma configuração.
	$module_settings->test = "test";
	$asserts->expect_equal($module_settings->test, "test");
