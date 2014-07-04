<?php

	// Responsável pela inicialização do motor e alguns recursos essenciais.
	class awk {
		/** MÓDULO */
		// Instância do módulo do próprio motor.
		static private $module;

		/** AUTOLOADER */
		// Mapa de classes que podem ser carregadas através do motor.
		// @type array<string>;
		static private $class_mapper = [
			// Controlador de paths.
			"awk_path",

			// Controlador de erro.
			"awk_error",
		];

		// Carrega as classes do motor via SPL.
		static private function load_class($classname) {
			// Se localizar a classe no mapa, então será possível carregá-la.
			// Caso contrário, deixará que o PHP verifique em outro SPL, se houver.
			if(in_array($classname, self::$class_mapper)) {
				require_once __DIR__ . "/{$classname}.php";
				return;
			}
		}

		/** INIT */
		// Inicializa o motor.
		static public function init() {
			// Registra o método de autoloader.
			spl_autoload_register("self::load_class");

			// Inicia o módulo do próprio motor.
			self::$module = awk_module::get(self::class);
		}
	}
