<?php

	// Responsável por representar um registro de dados.
	class Awk_Model_Row extends Awk_Module_Base {
		// Armazena os resultados obtidos.
		// @type array<string, string>;
		private $data = [];

		// Armazena se o registro existe, de fato.
		// @type boolean;
		private $exists = false;

		/** RESULT */
		// Define os dados com base em um resultado obtido.
		public function set_result($query_result) {
			$this->data = $query_result->fetch(PDO::FETCH_ASSOC);
			$this->exists = !empty($this->data);
		}

		/** GET */
		// Obtém os dados armazenados como array.
		public function get_array() {
			return $this->data;
		}

		/** MAGIC */
		// Obtém uma informação do registro.
		public function __get($key) {
			return $this->data[$key];
		}

		// Redefine uma informação do registro.
		public function __set($key, $value) {
			$this->data[$key] = $value;
		}

		// Verifica se uma informação existe.
		public function __isset($key) {
			return array_key_exists($key, $this->data);
		}

		// Remove uma informação.
		public function __unset($key) {
			unset($this->data[$key]);
		}
	}
