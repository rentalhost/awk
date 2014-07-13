<?php

	// Responsável pela definição e verificação de rotas de roteador.
	class awk_router_route {
		// Armazena a definição da rota.
		// @type string;
		private $definition;

		// Armazena a definição compilada da rota.
		// @type array<mixed>;
		private $definition_compiled;

		// Armazena o callback da rota.
		// @type callback;
		private $callback;

		/** CONSTRUCT */
		// Construtor.
		public function __construct($route_definition, $route_callback) {
			$this->definition = $route_definition;
			$this->callback = $route_callback;
		}

		/** BUILD */
		// Compila a definição da rota, para que seja mais fácil verificar a URL.
		private function get_compiled() {
			// Se a definição já foi compilada, apenas a retorna.
			// Caso contrário, será necessário compilá-la.
			if($this->definition_compiled) {
				return $this->definition_compiled;
			}

			// Armazenará a definição compilada.
			$definition_compiled = [];

			// Divide a definição por "/", como uma URL.
			// Para cada parte, será necessário identificar o tipo da informação.
			$definition_parts = array_filter(explode("/", $this->definition), "strlen");
			foreach($definition_parts as $definition_part) {
				$definition_compiled[] = [ "method" => "static", "match" => $definition_part ];
			}

			// Após compilar, armazenará e retornará.
			return $this->definition_compiled = $definition_compiled;
		}

		/** MATCH */
		// Executa um teste de rota com a URL Array informada.
		public function match($url_array, &$output_args, &$url_array_index) {
			// Armazena a compilação da definição.
			$compiled = $this->get_compiled();

			// Verifica cada parte da definição.
			$compiled_index = 0;
			$compiled_length = count($compiled);
			$url_array_index = 0;
			$url_array_length = count($url_array);

			// Enquanto o index da compilação for menor que o seu tamanho, \
			// verifica se a URL será valiada.
			while($compiled_index < $compiled_length) {
				// Carrega as instruções do index atual da definição.
				$compiled_statements = $compiled[$compiled_index];

				// Falha imediatamente caso não haja mais partes da URL a ser testada.
				if(!isset($url_array[$url_array_index])) {
					return false;
				}

				// Carrega a parte que será testada.
				$url_array_part = $url_array[$url_array_index];

				// Se o método de instrução for "static", verifica se a parte da URL combina.
				if($compiled_statements["method"] === "static"
				&& $compiled_statements["match"] === $url_array_part) {
					$compiled_index++;
					$url_array_index++;
					continue;
				}

				// Falha o teste se todas as verificações também falharem.
				return false;
			}

			// Se em nenhum momento a rota foi invalidada, retornará sucesso.
			return true;
		}

		/** DEFINITION */
		// Retorna a definição da rota.
		// @return string;
		public function get_definition() {
			return $this->definition;
		}

		/** CALLBACK */
		// Retorna a callback armazenada.
		public function get_callback() {
			return $this->callback;
		}
	}
