<?php

	// Responsável pelo controle da feature helper.
	class awk_helper_feature extends awk_module_feature {
		// Armazena as instâncias das helpers.
		// @type array<string, awk_helper>;
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
			$helper_instance = new awk_helper($this->module, $this);
			$helper_instance->load($helper_id);

			return $this->helpers[$helper_id] = $helper_instance;
		}
	}
