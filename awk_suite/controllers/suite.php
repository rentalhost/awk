<?php

	// Responsável pelo controle da suite.
	class awk_suite_controller extends awk_base {
		// Página inicial.
		public function home() {
			$this->get_module()->view("home");
		}
	}

	// Registra a classe.
	$controller->register("awk_suite_controller");
