<?php

	// Responsável pela definição de uma parte de uma rota.
	class Awk_Router_Route_Part {
		// Armazena a rota responsável pela parte.
		// @type Awk_Router_Route;
		public $route;

		// Armazena o tipo de método de validação.
		// @type string;
		public $method = "static";

		// Armazena o valor que será validado.
		// @type string;
		public $match;

		// Armazena o gestor de tipo.
		// @type Awk_Type;
		public $match_type;

		// Armazena o nome do atributo.
		// @type string;
		public $match_attr;

		// Indica se a validação é opcional.
		// @type boolean;
		public $optional = false;

		// Indica se a parte poderá ser repetida.
		// @type boolean;
		public $repeat = false;

		// Armazena o número mínimo de repetições da parte.
		// @type int;
		public $repeat_min;

		// Armazena o número máximo de repetições da parte.
		// @type int;
		public $repeat_max;

		/** CONSTRUCT */
		// Constrói a definição da parte.
		public function __construct($route, $definition) {
			$this->route = $route;

			// Identifica uma configuração na parte.
			$definition_match = preg_match("/
				(?:
					(?<repeat_simple>\+|\*) |
					(?<repeat_exactly>\{\d+\}) |
					(?<repeat_min>\{\d+,\}) |
					(?<repeat_max>\{,\d+\}) |
					(?<repeat_range>\{\d+,\d+\})
				)?
				(?<optional>\?)?
			$/x", $definition, $definition_options);

			if($definition_match) {
				// Define se identificar um opcional.
				if(!empty($definition_options["optional"])) {
					$this->optional = true;
				}

				// Define um repetidor simples.
				if(!empty($definition_options["repeat_simple"])) {
					$this->repeat = true;
					$this->repeat_min = intval($definition_options["repeat_simple"] === "+");
				}
				else
				// Define um repetidor exato.
				if(!empty($definition_options["repeat_exactly"])) {
					$this->repeat = true;
					$this->repeat_min =
					$this->repeat_max = intval(substr($definition_options["repeat_exactly"], 1, -1));
				}
				else
				// Define um repetidor mínimo.
				if(!empty($definition_options["repeat_min"])) {
					$this->repeat = true;
					$this->repeat_min = intval(substr($definition_options["repeat_min"], 1, -2));
				}
				else
				// Define um repetidor máximo.
				if(!empty($definition_options["repeat_max"])) {
					$this->repeat = true;
					$this->repeat_max = intval(substr($definition_options["repeat_max"], 2, -1));
				}
				else
				// Define um repetidor de alcance.
				if(!empty($definition_options["repeat_range"])) {
					$repeat_range = explode(",", substr($definition_options["repeat_range"], 1, -1));

					$this->repeat = true;
					$this->repeat_min = intval($repeat_range[0]);
					$this->repeat_max = intval($repeat_range[1]);
				}

				// Remove a configuração da parte.
				if(!empty($definition_options[0])) {
					$definition = substr($definition, 0, -strlen($definition_options[0]));
				}
			}

			// Verifica se a definição é um tipo.
			$definition_type = preg_match("/^
				\[
				(?<identifier>[^\s]+)
				(?:\s+\@(?<attr_name>[a-zA-Z][a-zA-Z0-9_\-]*))?
				\]
			$/x", $definition, $definition_match);

			if($definition_type) {
				$this->method = "typed";

				// Carrega a identificação para definir o tipo de combinação.
				$this->match_type = $this->route->get_router()->get_module()->identify(rtrim($definition_match["identifier"]), "type", true);

				// Se um nome de atributo foi definido, então aplica.
				if(!empty($definition_match["attr_name"])) {
					$this->match_attr = $definition_match["attr_name"];
				}

				return;
			}

			// Armazena a definição de validação.
			$this->match = $definition;
		}

		/** MATCH */
		// Verifica se uma parte da URL pode ser processada pela definição.
		public function match($url) {
			// Se for o método "static", é uma validação simples.
			if($this->method === "static"
			&& $this->match === $url) {
				return true;
			}

			// Se for o método "type", é uma validação por tipo.
			if($this->method === "typed"
			&& $this->match_type->validate($url)) {
				return true;
			}

			// Caso contrário, não combina.
			return false;
		}
	}
