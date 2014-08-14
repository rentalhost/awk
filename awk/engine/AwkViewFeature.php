<?php

	// Responsável pelo controle da feature view.
	class AwkViewFeature extends AwkModuleFeature {
		/** FEATURE CALL */
		// Carrega uma view imediatamente.
		public function feature_call($view_id, $view_args = null, $view_avoid_print = null) {
			return $this->load($view_id, $view_args, $view_avoid_print);
		}

		// Carrega uma view diretamente.
		public function load($view_id, $view_args = null, $view_avoid_print = null) {
			$view_instance = new AwkView($this->module, $this);
			$view_instance->load($view_id, $view_args, $view_avoid_print);

			return $view_instance;
		}
	}
