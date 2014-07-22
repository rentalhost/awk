<?php

	// Responsável pelo controle de asserts.
	class awk_suite_asserts_controller extends awk_base {
		// Armazena o grupo de asserts.
		//@type array<library("asserts/file")>;
		private $asserts_files = [];

		// Inicia o processamento de asserts.
		public function run() {
			$asserts_dir = $this->get_module()->file("asserts");
			foreach($asserts_dir->get_files() as $assert_file) {
				$assert_file_instance = $this->get_module()->library("asserts/file")->create();
				$assert_file_instance->run($assert_file);

				$this->asserts_files[] = $assert_file_instance;
			}
		}

		// Após processar, retorna o conteúdo obtido.
		public function get_contents($options) {
			$ignore_successes = isset($options["ignore-successes"]) ? (bool) $options["ignore-successes"] : false;

			// Número de falhas.
			$fail_count = 0;

			// Define o que será impresso.
			$assert_contents = [];
			foreach($this->asserts_files as $assert_file) {
				// Se não houver erros, e for necessário ignorar sucessos, avança.
				if($ignore_successes === true
				&& !$assert_file->has_fails()) {
					continue;
				}

				// Cada arquivo contém um grupo de testes.
				// Verifica a resposta de cada grupo.
				$group_contents = [];
				foreach($assert_file->get_unities() as $assert_unit) {
					// Se não houver falha, e for necessário ignorar, avança.
					if($ignore_successes === true
					&& $assert_unit->get_success()) {
						continue;
					}

					$group_contents[] = $this->get_module()->view("asserts/item", [
						"line" => $assert_unit->get_line(),
						"title" => $assert_unit->get_title(),
						"description" => $assert_unit->get_description(),
						"status" => $assert_unit->get_success() ? "success" : "fail",
						"fail_message" => $assert_unit->get_fail_message()
					], true);

					if(!$assert_unit->get_success()) {
						$fail_count++;
					}
				}

				// Compila o grupo.
				$assert_contents[] = $this->get_module()->view("asserts/group", [
					"title" => $assert_file->get_name(),
					"contents" => join("\n", $group_contents)
				], true);
			}

			// Define a mensagem do rodapé.
			$footer_message = $fail_count === 0
				? "Verificação finalizada, sem falhas."
				: "Verificação finalizada, {$fail_count} falha(s).";

			// Retorna o resultado final obtido
			return $this->get_module()->view("asserts/widget", [
				"contents" => join("\n", $assert_contents),
				"footer_message" => $footer_message
			], true);
		}
	}

	// Registra o controller.
	$controller->register("awk_suite_asserts_controller");
