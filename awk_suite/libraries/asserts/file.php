<?php

	// Esta library é responsável pelo controle de execução de um arquivo.
	class AwkSuite_Asserts_File_Library extends Awk_Base {
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
			$value_b_typed = $this->type_helper->call("normalize", $value_b);

			// Define o assert.
			$assert_unit = $this->unit_library->create();
			$assert_unit->set_title("expect_equal(with {$value_b_typed})");
			$assert_unit->set_description($description);
			$assert_unit->set_success($value_a === $value_b);

			// Caso não haja sucesso.
			if(!$assert_unit->get_success()) {
				$this->fail_count++;

				$value_a_typed = $this->type_helper->call("normalize", $value_a);
				$assert_unit->set_fail_message("expected {$value_b_typed}, but received {$value_a_typed}.");
			}

			$this->asserts_unities[] = $assert_unit;
		}

		// Espera um valor capturado no callback.
		public function expect_capture($callback, $expect_value, $description = null) {
			// Captura o valor.
			ob_start();
			call_user_func($callback);
			$callback_capture = ob_get_clean();

			// Define o assert.
			$assert_unit = $this->unit_library->create();
			$assert_unit->set_title("expect_capture(value \"{$expect_value}\")");
			$assert_unit->set_description($description);
			$assert_unit->set_success($callback_capture === $expect_value);

			// Caso não haja sucesso.
			if(!$assert_unit->get_success()) {
				$this->fail_count++;
				$assert_unit->set_fail_message("expected \"{$expect_value}\", but received \"{$callback_capture}\".");
			}

			$this->asserts_unities[] = $assert_unit;
		}

		// Espera uma exceção.
		public function expect_exception($callback, $exception_type, $expection_message = null, $description = null) {

			// Armazena a instância da exceção, se lançada.
			$exception_instance = null;

			// Executa a função, esperando uma exceção.
			try { $callback_return = call_user_func($callback); }
			catch(Exception $exception_instance) {}

			// VERIFICA O TIPO DA EXCEÇÃO.
			// Define os dados da exceção.
			$assert_unit = $this->unit_library->create();
			$assert_unit->set_title("expect_exception(type {$exception_type})");
			$assert_unit->set_description($description);
			$assert_unit->set_success(
				$exception_instance !== null
			 && $exception_instance instanceof $exception_type
			);

			// Define a mensagem de falha.
			if(!$assert_unit->get_success()) {
				$this->fail_count++;

				// Armazena o tipo de erro.
				$assert_fail_message = "expected exception of type {$exception_type}, but nothing thrown.";
				if($exception_instance !== null) {
					$assert_fail_message = "expected exception of type {$exception_type}, but received " . get_class($exception_instance) . ".";
				}

				// Define a mensagem de falha.
				$assert_unit->set_fail_message($assert_fail_message);
			}

			// Adiciona às unidades.
			$this->asserts_unities[] = $assert_unit;

			// VERIFICA A MENSAGEM RECEBIDA.
			// Se uma mensagem foi informada, então verifica se está correta.
			if($expection_message !== null
			&& $exception_instance !== null) {
				$assert_unit = $this->unit_library->create();
				$assert_unit->set_title("expect_exception(type {$exception_type}, message \"{$expection_message}\")");
				$assert_unit->set_description($description);
				$assert_unit->set_success($exception_instance->getMessage() === $expection_message);

				// Define a mensagem de falha.
				if(!$assert_unit->get_success()) {
					$this->fail_count++;

					// Define a mensagem de falha.
					$assert_unit->set_fail_message("expected exception of type {$exception_type} with message \"{$expection_message}\", but received \"" .
						$exception_instance->getMessage() . "\".");
				}

				// Adiciona às unidades.
				$this->asserts_unities[] = $assert_unit;
			}
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
	$library->register("AwkSuite_Asserts_File_Library");
