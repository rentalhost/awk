<?php

	// Responsável pelo controle da feature database.
	class awk_database_feature extends awk_module_feature {
		// Armazena as instâncias das databases.
		// @type array<string, awk_database>;
		private $databases = [
		];

		/** FEATURE CALL */
		// Retorna o controle da database.
		public function feature_call($database_id = null) {
			$database_id = $database_id ?: "default";

			// Se já foi registrado, retorna.
			// Caso contrário será necessário carregá-lo.
			if(isset($this->databases[$database_id])) {
				return $this->databases[$database_id];
			}

			// Carrega e retorna.
			return $this->databases[$database_id] = new awk_database($this->module, $this);
		}
	}
