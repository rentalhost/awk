<?php

	// Define a constante de testes.
	define("UNIT_TESTING", true);

	// Diretório do motor.
	$basedir = realpath("../awk/");

	// Carrega as classes básicas do motor.
	require_once "{$basedir}/engine/Awk.php";

	// Registra o motor.
	Awk::register();
