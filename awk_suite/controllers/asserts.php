<?php

	// Responsável pelo controle de asserts.
	class AwkSuite_Asserts_Controller extends AwkBase {
		// Armazena o grupo de asserts.
		//@type array<library("asserts/file")>;
		private $asserts_files = [];

		// Armazena o tempo total de execução.
		//@type array<float>;
		private $asserts_times = [];

		// Inicia o processamento de asserts.
		public function run($options) {
			// Verifica se deve ativar o Coverage.
			$enable_coverage = isset($options["enable-coverage"]) ? (bool) $options["enable-coverage"] : false;
			if($enable_coverage) {
				$coverage_filter = new PHP_CodeCoverage_Filter;
				$coverage_filter->addDirectoryToWhitelist(__DIR__ . "/../../awk/*/");

				$coverage = new PHP_CodeCoverage(null, $coverage_filter);
				$coverage->start("AwkSuite");
			}

			$asserts_dir = $this->get_module()->file("asserts");
			foreach($asserts_dir->get_files() as $assert_file) {
                $assert_file_instance = $this->get_module()->library("asserts/file")->create();

				$assert_time_start = microtime(true);
				$assert_file_instance->run($assert_file);
				$this->asserts_times[] = microtime(true) - $assert_time_start;

				$this->asserts_files[] = $assert_file_instance;
			}

			if($enable_coverage) {
				$coverage->stop();

				$suite_settings = $this->get_module()->settings();

				$writer = new PHP_CodeCoverage_Report_HTML;
				$writer->process($coverage, $suite_settings->coverage_output_dir);
			}
		}

		// Após processar, retorna o conteúdo obtido.
		public function get_contents($options) {
			$suite_settings = $this->get_module()->settings();

			// Verifica se deve ignorar os sucessos.
			$ignore_successes = isset($options["ignore-successes"]) ? (bool) $options["ignore-successes"] : false;
			$enable_coverage = isset($options["enable-coverage"]) ? (bool) $options["enable-coverage"] : false;

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

			// Obtém o tempo utilizado.
			$assert_time = array_sum($this->asserts_times);

			// Define a mensagem do rodapé.
			$footer_message = $fail_count === 0
				? "Verificação finalizada, sem falhas."
				: "Verificação finalizada, {$fail_count} falha(s).";
			$footer_message.= sprintf("<div class=\"assert-footer-time\">Executado em %.2f segundo%s.</div>",
				$assert_time, $assert_time >= 2 ? "s" : null);

			// Retorna o resultado final obtido
			return $this->get_module()->view("asserts/widget", [
				"contents" => join("\n", $assert_contents),
				"coverage_path" => $enable_coverage
					? $this->get_module()->public("coverage/index.html")->get_url()
					: null,
				"footer_message" => $footer_message,
			], true);
		}
	}

	// Registra o controller.
	$controller->register("AwkSuite_Asserts_Controller");
