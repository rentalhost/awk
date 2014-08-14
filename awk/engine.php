<?php

	// Carrega as classes básicas do motor.
	require_once "engine/Awk.php";
	require_once "engine/AwkModuleBase.php";
	require_once "engine/AwkModuleFeature.php";
	require_once "engine/AwkModule.php";
	require_once "engine/AwkRouterFeature.php";
	require_once "engine/AwkRouterDriverStack.php";
	require_once "engine/AwkRouterDriver.php";
	require_once "engine/AwkRouterRoutePart.php";
	require_once "engine/AwkRouterRoute.php";
	require_once "engine/AwkRouter.php";
	require_once "engine/AwkSettingsFeature.php";
	require_once "engine/AwkSettings.php";

	// Inicia o autoloader do composer, se houver.
	$composer_autoloader = __DIR__ . "/../vendor/autoload.php";
	if(is_readable($composer_autoloader)) {
		require $composer_autoloader;
	}

	// Inicia o processo.
	awk::init();
