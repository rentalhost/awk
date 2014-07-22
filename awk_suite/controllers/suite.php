<?php

	// Responsável pelo controle da suite.
	class awk_suite_controller extends awk_base {
		// Base da masterpage.
		private function master_base($master_contents) {
			$this->get_module()->view("home", [
				"contents" => $master_contents
			]);
		}

		// Carrega a página inicial da suite, sem executar os testes.
		//@url /suite
		public function home_page() {
			$this->master_base(null);
		}

		// Carrega a página, executando os testes.
		//@url /suite/run
		public function home_run($options) {
			$assert_controller = $this->get_module()->controller("asserts");
			$assert_controller->run();

			$this->master_base($assert_controller->get_contents([
				"ignore-successes" => in_array("ignore-successes", $options)
			]));
		}
	}

	// Registra a classe.
	$controller->register("awk_suite_controller");
