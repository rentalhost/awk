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
	$router->add_route("router_view", "view@tests/hello");

	// Rota de router.
	$router->add_route("router_router", "router@tests/index3");

	// Rota de controller.
	$router->add_route("router_controller", "controller@tests/valid::router_controller");

	// Rota com argumentos.
	$router->add_route("arg/[awk->string @value]", function($driver) {
		echo $driver->get_attr("value");
	});

	// Adiciona uma rota com preserva de URL.
	$router->add_route("preserve/simple_route", function($driver) {
		echo "preserve->";
		$driver->preserve_url();
		$driver->redirect("tests/index2");
	});

	// Adiciona um redirect.
	$router->add_passage(function($driver) {
		echo "redirect(index2)->";
		$driver->redirect("tests/index2");
	});
