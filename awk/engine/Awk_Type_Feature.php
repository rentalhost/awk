<?php

	/**
	 * Responsável pelo controle da feature type.
	 */
	class Awk_Type_Feature extends Awk_Module_Feature {
		/**
		 * Armazena as instâncias das types.
		 * @var Awk_Type[]
		 */
		private $types = [];

		/**
		 * Retorna o controle da type.
		 * @param  string $type_id Identificador do tipo.
		 * @return Awk_Type
		 */
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
