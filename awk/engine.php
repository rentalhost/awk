<?php

	// Carrega as classes básicas do motor.
	require_once "engine/awk.php";
	require_once "engine/awk_base.php";
	require_once "engine/awk_module_feature.php";
	require_once "engine/awk_module.php";
	require_once "engine/awk_router_feature.php";
	require_once "engine/awk_router_driver.php";
	require_once "engine/awk_router.php";

	// Inicia o processo.
	awk::init();
