<?php

	// Responsável pelo controle da feature view.
	class awk_view_feature extends awk_module_feature {
		/** FEATURE CALL */
		// Carrega uma view imediatamente.
		public function feature_call($view_id, $view_args = null, $view_unprint = null) {
			return $this->load($view_id, $view_args, $view_unprint);
		}

		// Carrega uma view diretamente.
		public function load($view_id, $view_args = null, $view_unprint = null) {
			$view_instance = new awk_view($this);
			$view_instance->load($view_id, $view_args);

			return $view_instance;
		}
	}
