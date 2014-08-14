<?php

	// Responsável por ser a base das classes registradas pelo usuário.
	// Se uma classe de usuário não estender esta classe, não terá acesso aos recursos do motor diretamente.
	class AwkBase {
		// Armazena o módulo responsável pelo objeto.
		// @type AwkModule;
		private $module;

		/** BASE */
		// Define os dados da base.
		public function set_base($module) {
			$this->module = $module;
		}

		/** MODULE */
		// Obtém o módulo.
		public function get_module() {
			return $this->module;
		}
	}
