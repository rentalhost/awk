<?php

	/**
	 * Responsável por representar um registro de dados.
	 */
	class Awk_Model_Row extends Awk_Module_Base {
		/**
		 * Armazena os resultados obtidos.
		 * @var mixed[]
		 */
		private $data = [];

		/**
		 * Armazena se o registro existe, de fato.
		 * @var boolean
		 */
		private $exists = false;

		/**
		 * Define os dados com base em um resultado obtido.
		 * @param PDO_Statement $query_result Resultado que será transferido.
		 */
		public function set_result($query_result) {
			$this->data = $query_result->fetch(PDO::FETCH_ASSOC);
			$this->exists = !empty($this->data);
		}

		/**
		 * Obtém os dados armazenados como array.
		 * @return mixed[]
		 */
		public function get_array() {
			return $this->data;
		}

		/**
		 * Obtém uma informação do registro.
		 * @param  string $key Chave que será obtida.
		 * @return mixed
		 */
		public function __get($key) {
			return $this->data[$key];
		}

		/**
		 * Redefine uma informação do registro.
		 * @param string $key   Chave que será redefinida.
		 * @param mixed  $value Valor que será aplicado.
		 */
		public function __set($key, $value) {
			$this->data[$key] = $value;
		}

		/**
		 * Verifica se uma informação existe.
		 * @param  string  $key Chave que será verificada.
		 * @return boolean
		 */
		public function __isset($key) {
			return array_key_exists($key, $this->data);
		}

		/**
		 * Remove uma informação.
		 * @param string $key Chave que será removida.
		 */
		public function __unset($key) {
			unset($this->data[$key]);
		}
	}
