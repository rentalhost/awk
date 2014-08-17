<?php

	// Responsável pela inicialização do motor e alguns recursos essenciais.
	class Awk {
		/** MÓDULO */
		// Instância do módulo do próprio motor.
		static private $module;

		/** AUTOLOADER */
		// Mapa de classes que podem ser carregadas através do motor.
		// @type array<string>;
		static private $class_mapper = [
			// Classes view.
			"Awk_View_Feature",
			"Awk_View",

			// Classes controller.
			"Awk_Controller_Feature",
			"Awk_Controller",

			// Classes library.
			"Awk_Library_Feature",
			"Awk_Library",

			// Classes helper.
			"Awk_Helper_Feature",
			"Awk_Helper",

			// Classes type.
			"Awk_Type_Feature",
			"Awk_Type",

			// Classes database.
			"Awk_Database_Feature",
			"Awk_Database",

			// Classes model.
			"Awk_Model_Feature",
			"Awk_Model_Query",
			"Awk_Model",
			"Awk_Model_Row",

			// Classes public.
			"Awk_Public_Feature",
			"Awk_Public",

			// Classes file.
			"Awk_File_Feature",
			"Awk_File",

			// Classes session.
			"Awk_Session_Feature",

			// Classes error.
			"Awk_Error",
			"Awk_Error_Exception",

			// Classes diversas.
			"Awk_Base",
			"Awk_Data",
			"Awk_Path",
		];

		// Carrega as classes do motor via SPL.
		/** @codeCoverageIgnore: o método é mapeado. */
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
		/** @codeCoverageIgnore: se falhar neste ponto, nada funcionará. */
		static public function init() {
			// Registra o método de autoloader.
			spl_autoload_register("self::load_class");

			// Inicia o módulo do próprio motor.
			self::$module = Awk_Module::get("awk");

			// Carrega as configurações do motor.
			$engine_settings = self::$module->settings();

			// Por definição é usado o "index" como roteador padrão, porém,\
			// quando vem de um arquivo público é necessário utilizar o roteador "index.file".
			$router_id = isset($_SERVER["REDIRECT_PUBLICS"])
				? $engine_settings->router_file_default
				: $engine_settings->router_default;

			// Identifica a rota.
			// Se o roteador existir, ele será utilizado.
			$router_identify = self::$module->identify($router_id, "router", null, true, true);
			if($router_identify["module"]->routers->exists($router_identify["name"])) {
				$router_driver = new Awk_Router_Driver(Awk_Router::get_url(), $router_identify["module"], true);
				$router_driver->redirect($router_id);
				return;
			}

			// Caso contrário, será forçado um erro de página (404).
			Awk_Error::force_404();
		}
	}
