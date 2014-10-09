<?php

	/**
	 * Responsável pelo controle da feature public.
	 */
	class Awk_Public_Feature extends Awk_Module_Feature {
		/**
		 * Carrega uma instância imediatamente.
		 * @param  string     $public_name Identificador do arquivo público.
		 * @return Awk_Public
		 */
		public function feature_call($public_name) {
			return $this->load($public_name);
		}

		/**
		 * Carrega uma instância imediatamente.
		 * @param  string     $public_name Identificador do arquivo público.
		 * @return Awk_Public
		 */
		public function load($public_name) {
			$public_instance = new Awk_Public($this->module, $this);
			$public_instance->load($public_name);

			return $public_instance;
		}
	}
