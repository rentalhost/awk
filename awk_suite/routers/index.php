<?php

	// Define uma passagem.
	$router->add_passage(function($driver) use($router) {
		printf("Você está em <code>%s</code>.", $router->get_id());
	});
