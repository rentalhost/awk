<?php

	// Configura uma conexão.
	$module->database()->configure($module->settings()->database_configure);

	// Carrega a conexão (Coverage).
	$database = $module->database();
	$database->query("SELECT TRUE");
	$database->query("SELECT TRUE");
