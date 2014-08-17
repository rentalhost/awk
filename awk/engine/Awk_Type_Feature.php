<?php

	// Responsável pelo controle da feature type.
	class Awk_Type_Feature extends Awk_Module_Feature {
		// Armazena as instâncias das types.
		// @type array<string, Awk_Type>;
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
			$type_instance = new Awk_Type($this->module, $this);
			$type_instance->load($type_id);

			return $this->types[$type_id] = $type_instance;
		}
	}
