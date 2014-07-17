<?php

	// Responsável pela definição e verificação de rotas de roteador.
	class awk_router_route {
		// Armazena o roteador responsável pela rota.
		// @type awk_router;
		private $router;

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
		public function __construct($private_array) {
			foreach($private_array as $key => $value) {
				$this->{$key} = $value;
			}
		}

		/** ROUTER */
		// Retorna o roteador da rota.
		public function get_router() {
			return $this->router;
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
				$definition_compiled[] = new awk_router_route_part($this, $definition_part);
			}

			// Após compilar, armazenará e retornará.
			return $this->definition_compiled = $definition_compiled;
		}

		/** MATCH */
		// Executa um teste de rota com a URL Array informada.
		public function match($url_array, &$output_args, &$output_attrs, &$url_array_index) {
			// Definição de parâmetros.
			$url_array_index = 0;
			$output_args = [];
			$output_attrs = [];

			// Se não houver uma definição, é uma rota de passagem.
			// Sempre deverá ser executada.
			if(!$this->definition) {
				return true;
			}

			// Armazena a compilação da definição.
			$definition = $this->get_compiled();

			// Verifica cada parte da definição.
			$definition_index = 0;
			$definition_length = count($definition);
			$url_array_length = count($url_array);

			// Armazena a captura atual de dados.
			$output_value = null;

			// Armazena o número de vezes que uma mesma parte da URL foi capturada.
			$url_array_matched = 0;

			// Enquanto o index da compilação for menor que o seu tamanho, \
			// verifica se a URL será valiada.
			while($definition_index < $definition_length) {
				// Carrega as instruções do index atual da definição.
				$definition_part = $definition[$definition_index];

				// Carrega a parte que será testada.
				$url_array_part = isset($url_array[$url_array_index])
					? $url_array[$url_array_index]
					: null;

				// Se for a primeira parte a ser testada, define o tipo de coleta.
				// Isto dependerá se a definição pode repetir ou não.
				if($url_array_matched === 0) {
					$output_value = $definition_part->repeat === true ? [] : null;
				}

				// Se a parte puder processar a URL, então valida.
				if($definition_part->match($url_array_part)) {
					$url_array_matched++;
					$url_array_index++;

					// Armazena o valor processado, se for "typed".
					if($definition_part->method === "typed") {
						$definition_match_value = $definition_part->match_type->transform($url_array_part);
						if($definition_part->repeat === true) {
							$output_value[] = $definition_match_value;
						}
						else {
							$output_value = $definition_match_value;
						}
					}

					// Se for necessário repetir a ação, avança.
					// Também é necessário respeitar os limites máximos de repetição.
					if($definition_part->repeat) {
						if($definition_part->repeat_max === null
						|| $url_array_matched < $definition_part->repeat_max) {
							continue;
						}
					}
				}

				// Se houver repetição e o número mínimo de atributos não foi atingido, retorna false.
				if($definition_part->repeat
				&& $url_array_matched < $definition_part->repeat_min) {
					if(!$definition_part->optional) {
						return false;
					}

					// Entretanto, se for opcional, apenas volta alguns passos do proceso, \
					// e limpa o buffer de saída.
					$url_array_index-= count($output_value);
					$output_value = [];
				}

				// Se ao menos uma parte foi validada, \
				// ou a parte é opcional, \
				// ou é possível apenas uma única repetição, então avança.
				if($url_array_matched !== 0
				|| $definition_part->optional === true
				|| $definition_part->repeat_min === 0) {
					$url_array_matched = 0;
					$definition_index++;

					// Armazena o valor processado, se for "typed".
					if($definition_part->method === "typed") {
						$output_args[] = $output_value;

						// Se for necessário, define também como um atributo.
						if($definition_part->match_attr) {
							$output_attrs[$definition_part->match_attr] = $output_value;
						}
					}

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
