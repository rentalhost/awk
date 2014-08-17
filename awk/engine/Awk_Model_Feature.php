<?php

	// Responsável pelo controle da feature model.
	class Awk_Model_Feature extends Awk_Module_Feature {
		// Armazena as instâncias das models.
		// @type array<string, Awk_Model>;
		private $models = [];

		/** FEATURE CALL */
		// Retorna o controle da model.
		public function feature_call($model_id) {
			// Se já foi registrado, retorna.
			// Caso contrário será necessário carregá-lo.
			if(isset($this->model[$model_id])) {
				return $this->model[$model_id];
			}

			// Carrega e retorna.
			$model_instance = new Awk_Model($this->module, $this);
			$model_instance->load($model_id);

			return $this->model[$model_id] = $model_instance;
		}
	}
