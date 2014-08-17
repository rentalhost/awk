<?php

	// Responsável pelo controle da feature view.
	class Awk_View_Feature extends Awk_Module_Feature {
		/** FEATURE CALL */
		// Carrega uma view imediatamente.
		public function feature_call($view_id, $view_args = null, $view_avoid_print = null) {
			return $this->load($view_id, $view_args, $view_avoid_print);
		}

		// Carrega uma view diretamente.
		public function load($view_id, $view_args = null, $view_avoid_print = null) {
			$view_instance = new Awk_View($this->module, $this);
			$view_instance->load($view_id, $view_args, $view_avoid_print);

			return $view_instance;
		}
	}
