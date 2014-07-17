<?php

	// Responsável pelo modelo de dados da view.
	class awk_view extends awk_base {
		static protected $feature_type = "view";

		// Armazena se o caminho da view remete a um arquivo acessível.
		// @type boolean;
		private $exists = false;

		// Armazena a informação retornada pela view.
		// @type mixed;
		private $return;

		// Armazena a informação impressa pela view.
		// @type string;
		private $contents;

		// Armazena se a view foi impressa.
		// @type boolean;
		private $printed = false;

		/** LOAD */
		// Carrega a view e a retorna.
		// @return self;
		public function load($view_name, $view_args = null, $view_unprint = null) {
			$this->name = $view_name;
			$this->path = $this->module->get_path() . "/views/{$view_name}.php";
			$this->exists = is_readable($this->path);

			// Carrega o arquivo, armazenando sua saída.
			ob_start();
			$this->return = $this->module->include_clean($this->path, $view_args);
			$this->contents = ob_get_clean();

			// Imprime a view, se for necessário.
			if($view_unprint !== false) {
				$this->print_contents();
			}
		}

		/** PRINT */
		// Imprime a view.
		// Nota: este método deve ser usado quando for necessário imprimir o conteúdo,
		//       exceto quando for necessário uma impressão transparente.
		public function print_contents() {
			$this->printed = true;
			echo $this->contents;
		}

		// Retorna se a view já foi impressa.
		public function was_printed() {
			return $this->printed;
		}

		/** PROPRIEDADES */
		// Obtém o identificador da view.
		// @return string;
		public function get_name() {
			return $this->id;
		}

		// Obtém o path normalizado da view.
		// @return string;
		public function get_path() {
			return awk_path::normalize($this->path);
		}

		// Retorna o *return* da view, após ser carregada.
		// @return mixed;
		public function get_return() {
			return $this->return;
		}

		// Retorna o conteúdo gerado pela view.
		// @return string;
		public function get_contents() {
			return (string) $this->contents;
		}

		// Aliases para `get_contents()`.
		// @return string;
		public function __toString() {
			return $this->get_contents();
		}

		// Retorna se o arquivo da view existe ou não.
		// @return boolean;
		public function exists() {
			return $this->exists;
		}
	}
