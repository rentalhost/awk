<?php

	// Argumento simples.
	$test_driver = new awk_router_driver("args/simple", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "simple");

	// Argumentos opcionais.
	$test_driver = new awk_router_driver("args/123", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "123,,");

	$test_driver = new awk_router_driver("args/123/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "123,,abc");

	$test_driver = new awk_router_driver("args/123/1.5/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "123,1.5,abc");

	$test_driver = new awk_router_driver("args/1.5/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, ",1.5,abc");

	$test_driver = new awk_router_driver("args/1.5", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, ",1.5,");

	// Argumentos com repetição simples (+).
	$test_driver = new awk_router_driver("repeat/simple-one/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Argumentos com repetição simples (*).
	$test_driver = new awk_router_driver("repeat/simple-zero/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "");

	$test_driver = new awk_router_driver("repeat/simple-zero/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Argumentos com repetição exata.
	$test_driver = new awk_router_driver("repeat/exactly/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Argumentos com repetição mínima.
	$test_driver = new awk_router_driver("repeat/min/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	$test_driver = new awk_router_driver("repeat/min/1/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "fail");

	// Argumentos com repetição mínima e opcional.
	$test_driver = new awk_router_driver("repeat/min-optional/1/2/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, ",1");

	// Argumentos com repetição maxima.
	$test_driver = new awk_router_driver("repeat/max/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	$test_driver = new awk_router_driver("repeat/max/1/2/3/4/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Argumentos com repetição no alcance.
	$test_driver = new awk_router_driver("repeat/ranged/1/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "fail");

	$test_driver = new awk_router_driver("repeat/ranged/1/2/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2");

	$test_driver = new awk_router_driver("repeat/ranged/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	$test_driver = new awk_router_driver("repeat/ranged/1/2/3/4/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Falha.
	$test_driver = new awk_router_driver("fail", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "fail");
