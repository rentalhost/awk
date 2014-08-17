<?php

	// Responsável pelo controle da feature helper.
	class Awk_Helper_Feature extends Awk_Module_Feature {
		// Armazena as instâncias das helpers.
		// @type array<string, Awk_Helper>;
		private $helpers = [
		];

		/** FEATURE CALL */
		// Retorna o controle da helper.
		public function feature_call($helper_id) {
			// Se já foi registrado, retorna.
			// Caso contrário será necessário carregá-lo.
			if(isset($this->helpers[$helper_id])) {
				return $this->helpers[$helper_id];
			}

			// Carrega e retorna.
			$helper_instance = new Awk_Helper($this->module, $this);
			$helper_instance->load($helper_id);

			return $this->helpers[$helper_id] = $helper_instance;
		}
	}
