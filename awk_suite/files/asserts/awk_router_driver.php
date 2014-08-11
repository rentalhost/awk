<?php

	// Raíz.
	$test_driver = new awk_router_driver("", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "root");

	// Rota simples.
	$test_driver = new awk_router_driver("simple_route", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "passage->simple_route");

	// Obtém os dados do router.
	$test_driver = new awk_router_driver("get_router", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "passage->awk_router");

	// Rota através de uma identity view.
	$test_driver = new awk_router_driver("router_view", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "passage->Hello World!");

	// Rota através de uma identity router.
	$test_driver = new awk_router_driver("router_router", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "passage->index3");

	// Rota através de uma identity controller.
	$test_driver = new awk_router_driver("router_controller", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "passage->router_controller");

	// Rota com obtenção de argumentos.
	$test_driver = new awk_router_driver("arg/hello world!", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "passage->hello world!");

	// Rota simples, com preserve.
	$test_driver = new awk_router_driver("preserve/simple_route", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "passage->preserve->simple_route_preserved");

	// Rota simples, em outro router.
	$test_driver = new awk_router_driver("simple_other", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "passage->redirect(index2)->simple_other");

	// Rota com falha.
	$test_driver = new awk_router_driver("fail", $module);
	$asserts->expect_capture(function() use($test_driver) {
		$test_driver->redirect("tests/index");
	}, "passage->redirect(index2)->");
