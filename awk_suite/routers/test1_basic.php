<?php

	// Adiciona um root.
	$router->add_root(function() {
		echo "root";
	});

	// Adiciona uma passagem.
	$router->add_passage(function($driver) {
		echo "passage->";
		$driver->invalidate();
	});

	// Adiciona um valor estático (simples).
	$router->add_route("simple_route", function() {
		echo "simple_route";
	});

	// Obtém os dados do router.
	$router->add_route("get_router", function($driver) {
		echo get_class($driver->get_router());
	});

	// Rota de view.
	$router->add_route("router_view", "view@test1");

	// Rota de router.
	$router->add_route("router_router", "router@test3_router");

	// Rota de controller.
	$router->add_route("router_controller", "controller@test1_valid::router_controller");

	// Rota com argumentos.
	$router->add_route("arg/[awk->string @value]", function($driver) {
		echo "captured[" . $driver->get_attr("value") . "]";
	});

	// Adiciona uma rota com preserva de URL.
	$router->add_route("preserve/simple_route", function($driver) {
		echo "preserved[]->";
		$driver->preserve_url();
		$driver->redirect("test2_router");
	});

	// Adiciona um redirect.
	$router->add_passage(function($driver) {
		echo "redirected[test2_router]->";
		$driver->redirect("test2_router");
	});
