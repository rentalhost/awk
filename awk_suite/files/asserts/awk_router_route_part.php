<?php

	// Argumento simples.
	$test_driver = new AwkRouterDriver("args/simple", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "simple");

	// Argumentos opcionais.
	$test_driver = new AwkRouterDriver("args/123", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "123,,");

	$test_driver = new AwkRouterDriver("args/123/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "123,,abc");

	$test_driver = new AwkRouterDriver("args/123/1.5/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "123,1.5,abc");

	$test_driver = new AwkRouterDriver("args/1.5/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, ",1.5,abc");

	$test_driver = new AwkRouterDriver("args/1.5", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, ",1.5,");

	// Argumentos com repetição simples (+).
	$test_driver = new AwkRouterDriver("repeat/simple-one/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Argumentos com repetição simples (*).
	$test_driver = new AwkRouterDriver("repeat/simple-zero/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "");

	$test_driver = new AwkRouterDriver("repeat/simple-zero/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Argumentos com repetição exata.
	$test_driver = new AwkRouterDriver("repeat/exactly/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Argumentos com repetição mínima.
	$test_driver = new AwkRouterDriver("repeat/min/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	$test_driver = new AwkRouterDriver("repeat/min/1/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "fail");

	// Argumentos com repetição mínima e opcional.
	$test_driver = new AwkRouterDriver("repeat/min-optional/1/2/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, ",1");

	// Argumentos com repetição maxima.
	$test_driver = new AwkRouterDriver("repeat/max/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	$test_driver = new AwkRouterDriver("repeat/max/1/2/3/4/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Argumentos com repetição no alcance.
	$test_driver = new AwkRouterDriver("repeat/ranged/1/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "fail");

	$test_driver = new AwkRouterDriver("repeat/ranged/1/2/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2");

	$test_driver = new AwkRouterDriver("repeat/ranged/1/2/3/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	$test_driver = new AwkRouterDriver("repeat/ranged/1/2/3/4/abc", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "1,2,3");

	// Falha.
	$test_driver = new AwkRouterDriver("fail", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/special");
	}, "fail");
