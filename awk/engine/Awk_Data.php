<?php

	// Responsável pro gerir dados.
	class Awk_Data {
		// Armazena os dados.
		// @type array<string, mixed>;
		private $data = [];

		/** DATA */
		// Define uma variável.
		public function set($key, $value) {
			$this->data[$key] = $value;
		}

		// Define uma variável com referência.
		public function bind($key, &$value) {
			$this->data[$key] = &$value;
		}

		// Obtém uma variável.
		public function get($key) {
			return $this->data[$key];
		}

		/** GET ALL */
		// Retorna todas as variáveis definidas.
		public function get_all() {
			return $this->data;
		}

		/** CLEAR */
		// Remove todos os dados armazenados.
		public function clear() {
			$this->data = [];
		}

		/** MAGIC */
		// Define uma variável.
		public function __set($key, $value) {
			$this->data[$key] = $value;
		}

		// Obtém uma variável.
		public function __get($key) {
			return $this->data[$key];
		}

		// Verifica se uma variável existe.
		public function __isset($key) {
			return array_key_exists($key, $this->data);
		}

		// Remove uma variavel.
		public function __unset($key) {
			unset($this->data[$key]);
		}
	}
