<?php

	// Responsável pelo controle da feature library.
	class AwkLibraryFeature extends AwkModuleFeature {
		// Armazena as instâncias das libraries.
		// @type array<string, AwkLibrary>;
		private $libraries = [
		];

		/** FEATURE CALL */
		// Retorna o gerador de instâncias da library.
		public function feature_call($library_id) {
			// Se o gerador já foi registrado, retorna.
			// Caso contrário será necessário carregá-lo.
			if(isset($this->libraries[$library_id])) {
				return $this->libraries[$library_id];
			}

			// Carrega o gerador e retorna.
			$library_instance = new AwkLibrary($this->module, $this);
			$library_instance->load($library_id);

			return $this->libraries[$library_id] = $library_instance;
		}
	}
