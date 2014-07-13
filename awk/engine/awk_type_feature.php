<?php

	// Responsável pelo controle da feature type.
	class awk_type_feature extends awk_module_feature {
		// Armazena as instâncias das types.
		// @type array<string, awk_type>;
		private $types = [];

		/** FEATURE CALL */
		// Retorna o controle da type.
		public function feature_call($type_id) {
			// Se já foi registrado, retorna.
			// Caso contrário será necessário carregá-lo.
			if(isset($this->types[$type_id])) {
				return $this->types[$type_id];
			}

			// Carrega e retorna.
			$type_instance = new awk_type($this->module, $this);
			$type_instance->load($type_id);

			return $this->types[$type_id] = $type_instance;
		}
	}
