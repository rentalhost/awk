<?php

	// Responsável pelo controle da suite.
	class AwkSuite_Controller extends AwkBase {
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
			$assert_controller->run([
				"enable-coverage" => in_array("enable-coverage", $options)
			]);

			$this->master_base($assert_controller->get_contents([
				"ignore-successes" => in_array("ignore-successes", $options),
				"enable-coverage" => in_array("enable-coverage", $options)
			]));
		}
	}

	// Registra a classe.
	$controller->register("AwkSuite_Controller");
