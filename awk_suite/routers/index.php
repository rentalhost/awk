<?php

	// Define uma rota de fallback.
	$router->set_fallback(function() {
		echo "Hello World!";
	});
