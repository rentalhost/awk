<?php

	// Responsável por ser a base das classes registradas pelo usuário.
	// Se uma classe de usuário não estender esta classe, não terá acesso aos recursos do motor diretamente.
	class Awk_Base {
		// Armazena o módulo responsável pelo objeto.
		// @type Awk_Module;
		private $module;

		/** BASE */
		// Define os dados da base.
		public function set_base($module) {
			$this->module = $module;
		}
	}
