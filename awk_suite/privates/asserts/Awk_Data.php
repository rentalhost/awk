<?php

	$asserts->expect_equal($module->globals->get_all(), [ "test" => "ok" ]);

	$module->globals->set("a", "a");
	$module->globals->b = "b";

	$asserts->expect_equal($module->globals->get("a"), "a");
	$asserts->expect_equal($module->globals->b, "b");

	$c = 1;
	$module->globals->bind("c", $c);
	$asserts->expect_equal($module->globals->get("c"), 1);

	$c = 2;
	$asserts->expect_equal($module->globals->get("c"), 2);

	$asserts->expect_equal(isset($module->globals->c), true);

	unset($module->globals->c);
	$asserts->expect_equal(isset($module->globals->c), false);

	$module->globals->clear();
	$asserts->expect_equal($module->globals->get_all(), []);
