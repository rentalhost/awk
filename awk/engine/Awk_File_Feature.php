<?php

	// Responsável pelo controle da feature file.
	class Awk_File_Feature extends Awk_Module_Feature {
		/** FEATURE CALL */
		// Carrega uma instância imediatamente.
		public function feature_call($file_name) {
			return $this->load($file_name);
		}

		// Carrega uma instância diretamente.
		public function load($file_name) {
			$file_instance = new Awk_File($this->module, $this);
			$file_instance->load($file_name);

			return $file_instance;
		}
	}
