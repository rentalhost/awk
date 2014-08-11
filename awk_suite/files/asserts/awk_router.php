<?php

	$asserts->expect_equal(get_class($module->router("tests/index")), "awk_router");
	$asserts->expect_equal(count($module->router("tests/index")->get_routes()) > 0, true);
	$asserts->expect_equal(strpos($module->router("tests/index")->file_path(), "suite/run") !== false, true);
	$asserts->expect_equal(strpos($module->router("tests/index")->get_url(), "suite/run") !== false, true);

	$asserts->expect_equal($module->routers->exists("tests/index"), true);
