<?php

	// Carrega as classes básicas do motor.
	require_once "engine/Awk.php";
	require_once "engine/Awk_Module_Base.php";
	require_once "engine/Awk_Module_Feature.php";
	require_once "engine/Awk_Module.php";
	require_once "engine/Awk_Router_Feature.php";
	require_once "engine/Awk_Router_Driver_Stack.php";
	require_once "engine/Awk_Router_Driver.php";
	require_once "engine/Awk_Router_Route_Part.php";
	require_once "engine/Awk_Router_Route.php";
	require_once "engine/Awk_Router.php";
	require_once "engine/Awk_Settings_Feature.php";
	require_once "engine/Awk_Settings.php";

	// Inicia o autoloader do composer, se houver.
	$composer_autoloader = __DIR__ . "/../vendor/autoload.php";
	if(is_readable($composer_autoloader)) {
		require $composer_autoloader;
	}

	// Inicia o processo.
	Awk::register();
	Awk::init();
