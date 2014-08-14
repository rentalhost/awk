<?php

	// Responsável pelo controle da feature session.
	class AwkSessionFeature extends AwkModuleFeature {
		// Armazena a chave da sessão para o módulo.
		// @type string;
		private $session_key;

		/** SESSION KEY */
		// Inicia a chave da sessão.
		private function init_session_key() {
			// Define o caminho base, se não houver.
			if(!$this->session_key) {
				// Inicia a sessão.
				if(session_status() === PHP_SESSION_NONE) {
					session_start();
				}

				// Define a chave da sessão.
				$this->session_key = getcwd() . DIRECTORY_SEPARATOR . $this->get_module()->get_name();

				// Define a chave da sessão.
				if(!array_key_exists($this->session_key, $_SESSION)) {
					$this->clear();
				}
			}
		}

		/** FEATURE CALL */
		// Retorna o valor de uma sessão ou define o seu valor.
		public function feature_call($session_key = null, $session_value = null) {
			$this->init_session_key();

			switch(func_num_args()) {
				// Se não hover argumentos, retorna todos os dados da sessão.
				case 0:
					return $_SESSION[$this->session_key];
					break;
				// Com um argumento, obtém o valor da chave informada.
				case 1:
					return $_SESSION[$this->session_key][$session_key];
					break;
			}

			// Caso contrário, define o valor da sessão.
			$_SESSION[$this->session_key][$session_key] = $session_value;
		}

		/** MAGIC */
		// Verifica se uma sessão foi definida.
		public function __isset($session_key) {
			$this->init_session_key();
			return array_key_exists($session_key, $_SESSION[$this->session_key]);
		}

		// Remove a definição de uma sessão.
		public function __unset($session_key) {
			$this->init_session_key();
			unset($_SESSION[$this->session_key][$session_key]);
		}

		// Elimina todos os dados da sessão.
		public function clear() {
			$this->init_session_key();
			$_SESSION[$this->session_key] = [];
		}
	}
