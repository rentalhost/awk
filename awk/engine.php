<?php

	// Carrega as classes básicas do motor.
	require_once "engine/awk.php";
	require_once "engine/awk_module_base.php";
	require_once "engine/awk_module_feature.php";
	require_once "engine/awk_module.php";
	require_once "engine/awk_router_feature.php";
	require_once "engine/awk_router_driver_stack.php";
	require_once "engine/awk_router_driver.php";
	require_once "engine/awk_router_route_part.php";
	require_once "engine/awk_router_route.php";
	require_once "engine/awk_router.php";
	require_once "engine/awk_settings_feature.php";
	require_once "engine/awk_settings.php";

	// Inicia o autoloader do composer, se houver.
	$composer_autoloader = __DIR__ . "/../vendor/autoload.php";
	if(is_readable($composer_autoloader)) {
		require $composer_autoloader;
	}

	// Inicia o processo.
	awk::init();
