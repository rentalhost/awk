<?php

	// Responsável pelo controle da suite.
	class awk_suite_controller {
		// Página inicial.
		public function home() {
			var_dump("Hello World!");
		}
	}

	// Registra a classe.
	$controller->register("awk_suite_controller");
