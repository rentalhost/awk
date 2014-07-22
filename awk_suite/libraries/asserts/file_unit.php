<?php

	// Responsável pelas unidades de testes.
	class awk_suite_asserts_file_unit_library extends awk_base {
		// Armazena a linha do teste no arquivo.
		//@type int;
		private $line;

		// Armazena o título do teste.
		//@type string;
		private $title;

		// Armazena a descrição do teste.
		//@type string;
		private $description;

		// Indica se houve sucesso.
		//@type boolean;
		private $success;

		// Mensagem de falha.
		//@type string;
		private $fail_message;

		/** CONSTRUCT */
		// Define o arquivo.
		public function __construct() {
			foreach(debug_backtrace() as $debug_line) {
				if($debug_line["class"] === "awk_suite_asserts_file_library") {
					$this->line = $debug_line["line"];
					break;
				}
			}
		}

		/** PROPRIEDADES */
		// Obtém a linha do arquivo.
		public function get_line() {
			return $this->line;
		}

		// Determina o título do teste.
		public function set_title($title) {
			$this->title = $title;
		}

		// Obtém o título do teste.
		public function get_title() {
			return $this->title;
		}

		// Determina a descrição do teste.
		public function set_description($description) {
			$this->description = $description;
		}

		// Obtém a descrição do teste.
		public function get_description() {
			return $this->description;
		}

		// Determina se houve sucesso (ou não).
		public function set_success($success) {
			$this->success = (bool) $success;
		}

		// Obtém o status de sucesso.
		public function get_success() {
			return $this->success;
		}

		// Define a mensagem de falha.
		public function set_fail_message($fail_message) {
			$this->fail_message = $fail_message;
		}

		// Retorna a mensagem de falha.
		public function get_fail_message() {
			return $this->fail_message;
		}
	}

	// Registra a library.
	$library->register("awk_suite_asserts_file_unit_library");
