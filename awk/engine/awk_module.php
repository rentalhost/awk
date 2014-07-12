<?php

	// Responsável por gerenciar os módulos e suas propriedades.
	class awk_module {
		// Armazena as instâncias dos módulos carregados.
		// @type array<string, self>;
		static private $modules = [];

		/** ID */
		// Identificador do módulo.
		// @type string;
		private $id;

		// Caminho absoluto do módulo.
		// @type string;
		private $path;

		// Retorna o identificador do módulo.
		// @return string;
		public function get_id() {
			return $this->id;
		}

		// Retorna o caminho absoluto do módulo.
		// @return string;
		public function get_path() {
			return $this->path;
		}

		/** CONSTRUCT */
		// Constrói uma nova instância de módulo.
		private function __construct($module_id) {
			$this->id = $module_id;
			$this->path = __DIR__ . "/../../{$module_id}";

			// Se o caminho informado não existir, gera um erro.
			// @error generic;
			if(!is_dir($this->path)) {
				awk_error::create([
					"type" => awk_error::TYPE_FATAL,
					"message" => "O módulo \"{$module_id}\" não existe."
				]);
				return;
			}

			// Se o arquivo de configuração (settings.php) não existe, gera um erro.
			// Módulos devem possuir este arquivo para indicar um módulo valido.
			// @error generic;
			$module_settings_path = "{$this->path}/settings.php";
			if(!is_file($module_settings_path)) {
				awk_error::create([
					"type" => awk_error::TYPE_FATAL,
					"message" => "O módulo \"{$module_id}\" não definiu o arquivo de configuração."
				]);
			}
		}

		/** FEATURE */
		// Define um mapa de features, ligando a sua classe.
		// Os dados são informados pluralizados.
		// @type array<string, string>;
		static private $features_mapper = [
			"routers" => "awk_router_feature",
			"controllers" => "awk_controller_feature",
			"libraries" => "awk_library_feature",
			"helpers" => "awk_helper_feature",
			"views" => "awk_view_feature",
			"databases" => "awk_database_feature",
			"settings" => "awk_settings_feature",
		];

		// Armazena definições de plurais não linear.
		// Exemplo: library -> libraries;
		// @type array<string, string>;
		static private $features_normalizers = [
			"library" => "libraries",
			"settings" => "settings"
		];

		// Armazena as classes de features mapeadas do módulo.
		// @type array<string, instance>;
		private $features_instances = [
		];

		// Carrega uma feature através do seu nome singular.
		// @return instance;
		private function load_feature($name) {
			// Se a feature já foi carregada, a retorna.
			// Caso contrário será necessário carregá-la.
			if(isset($this->features_instances[$name])) {
				return $this->features_instances[$name];
			}

			// Verifica se é uma feature mapeada.
			// Se não for, será necessário lançar um erro.
			// @error generic;
			if(!isset(self::$features_mapper[$name])) {
				awk_error::create([
					"type" => awk_error::TYPE_FATAL,
					"message" => "O recurso \"{$name}\" não está disponível."
				]);
			}

			// Gera e retorna a instância da feature.
			$feature_reflection = new ReflectionClass(self::$features_mapper[$name]);
			return $this->features_instances[$name] = $feature_reflection->newInstance($this);
		}

		// Carrega e retorna a resposta da instância de uma feature via método.
		// @return mixed;
		public function __call($method, $method_args) {
			// Determina a pluralização do método para acessar o mapper.
			// Exemplo: view -> views;
			$method_plural =
				isset(self::$features_normalizers[$method])
				? self::$features_normalizers[$method]
				: "{$method}s";

			// Carrega a feature através do nome informado.
			$feature_instance = $this->load_feature($method_plural);

			// Executa a `feature_call()` passando os parâmetros recebidos.
			return call_user_func_array([ $feature_instance, "feature_call" ], $method_args);
		}

		// Carrega e retorna a instância de uma feature via propriedade.
		// @return instance;
		public function __get($key) {
			return $this->load_feature($key);
		}

		/** LOADER */
		// Carrega e retorna um módulo.
		static public function get($module_id) {
			// Se o módulo já foi carregado, retorna sua instância.
			if(isset(self::$modules[$module_id]))
				return self::$modules[$module_id];

			// Caso contrário, cria sua instância e retorna.
			return self::$modules[$module_id] = new self($module_id);
		}

		/** INCLUDE */
		// Inclui um arquivo com referência no módulo.
		// Nota: o nome dos parâmetros estarão disponível também no arquivo.
		// @return mixed;
		public function include_clean($include_file, $include_args = null) {
			// Define algumas variáveis básicas.
			$include_args = $include_args ?: [];
			$include_args["awk"] = self::$modules["awk"];
			$include_args["module"] = $this;

			// Extrai os argumentos para o arquivo.
			extract($include_args);

			// Inclui e retorna o valor do arquivo.
			return include $include_file;
		}
	}
