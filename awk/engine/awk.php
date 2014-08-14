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
			"AwkViewFeature",
			"AwkView",

			// Classes controller.
			"AwkControllerFeature",
			"AwkController",

			// Classes library.
			"AwkLibraryFeature",
			"AwkLibrary",

			// Classes helper.
			"AwkHelperFeature",
			"AwkHelper",

			// Classes type.
			"AwkTypeFeature",
			"AwkType",

			// Classes database.
			"AwkDatabaseFeature",
			"AwkDatabase",

			// Classes public.
			"AwkPublicFeature",
			"AwkPublic",

			// Classes file.
			"AwkFileFeature",
			"AwkFile",

			// Classes session.
			"AwkSessionFeature",

			// Classes error.
			"AwkError",
			"AwkErrorException",

			// Classes diversas.
			"AwkBase",
			"AwkPath",
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
			self::$module = AwkModule::get("awk");

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
				$router_driver = new AwkRouterDriver(AwkRouter::get_url(), $router_identify["module"], true);
				$router_driver->redirect($router_id);
				return;
			}

			// Caso contrário, será forçado um erro de página (404).
			AwkError::force_404();
		}
	}
