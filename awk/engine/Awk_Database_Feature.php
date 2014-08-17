<?php

	// Responsável pelo controle da feature database.
	class Awk_Database_Feature extends Awk_Module_Feature {
		// Armazena as instâncias das databases.
		// @type array<string, Awk_Database>;
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
			return $this->databases[$database_id] = new Awk_Database($this->module, $this);
		}
	}
