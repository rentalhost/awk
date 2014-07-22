<?php

	// Esta library é responsável pelo controle de execução de um arquivo.
	class awk_suite_asserts_file_library extends awk_base {
		// Armazena uma referência a library de unidades.
		//@type library("asserts/file_unit");
		private $unit_library;

		// Armazena a referência da helper type.
		//@type helper("type");
		private $type_helper;

		// Armazena o nome do grupo.
		//@type string;
		private $name;

		// Armazena os objetos de teste.
		//@type array<library("asserts/file_unit")>;
		private $asserts_unities = [];

		// Armazena o número de falhas obtidas.
		//@type int;
		private $fail_count = 0;

		/** RUN */
		// Executa a bateria de testes.
		public function run($assert_file) {
			$this->name = $assert_file;

			$this->unit_library = $this->get_module()->library("asserts/file_unit");
			$this->type_helper = $this->get_module()->helper("type");

			$this->get_module()->include_clean($assert_file, [ "asserts" => $this ]);
		}

		/** ASSERTS */
		// Espera um valor igual.
		public function expect_equal($value_a, $value_b, $description = null) {
			$assert_unit = $this->unit_library->create();

			$assert_unit->set_title("expect_equal(with {$value_b})");
			$assert_unit->set_description($description);
			$assert_unit->set_success($value_a === $value_b);

			if(!$assert_unit->get_success()) {
				$this->fail_count++;

				$value_b = $this->type_helper->call("normalize", $value_b);
				$value_a = $this->type_helper->call("normalize", $value_a);

				$assert_unit->set_fail_message("expected {$value_b}, but received {$value_a}.");
			}

			$this->asserts_unities[] = $assert_unit;
		}

		/** PROPRIEDADES */
		// Retorna o nome do grupo.
		public function get_name() {
			return $this->name;
		}

		// Retorna os resultados gerados.
		public function get_unities() {
			return $this->asserts_unities;
		}

		// Indica se houve falhas.
		public function has_fails() {
			return $this->fail_count > 0;
		}
	}

	// Registra a library.
	$library->register("awk_suite_asserts_file_library");
