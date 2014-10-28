<?php

    /**
     * Responsável pela definição e verificação de rotas de roteador.
     */
    class Awk_Router_Route {
        /**
         * Armazena o roteador responsável pela rota.
         * @var Awk_Router
         */
        private $router;

        /**
         * Armazena a definição da rota.
         * @var string
         */
        private $definition;

        /**
         * Armazena a definição compilada da rota.
         * @var (string|Awk_Syntax_Object)[]
         */
        private $definition_compiled;

        /**
         * Armazena o callback da rota.
         * @var callable
         */
        private $callback;

        /**
         * Armazena se é uma rota de arquivo.
         * @var boolean
         */
        private $is_file = false;

        /**
         * Construtor.
         * @param Awk_Router $router Roteador responsável pela construção.
         */
        public function __construct($router) {
            $this->router = $router;
        }

        /**
         * Compila a definição da rota, para que seja mais fácil verificar a URL.
         * @return mixed[]
         */
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
                // Se a informação começar por ":" considera um objeto de Syntax.
                if(substr($definition_part, 0, 1) === ":") {
                    $definition_compiled[] = Awk_Syntax_Object::create($this->router->module, $definition_part, "url");
                    continue;
                }

                // Caso contrário, adiciona a informação como string.
                $definition_compiled[] = $definition_part;
            }

            // Após compilar, armazenará e retornará.
            return $this->definition_compiled = $definition_compiled;
        }

        /**
         * Executa um teste de rota com a URL Array informada.
         * Retorna true se o processo foi validado com sucesso.
         *
             * Argumentos e atributos se diferem que, argumentos são gerados automaticamente,
         * em ordem do que foi solicitado. Enquanto atributos só são gerados quando
         * explicitamente são definidos.
         *
         * @param  string[] $url_array       Partes da URL que será testada.
         * @param  mixed[]  $output_args     Argumentos gerados pela URL.
         * @param  mixed[]  $output_attrs    Atributos gerados pela URL.
         * @param  integer  $url_array_index Ponto em que foi possível processar a URL.
         * @return boolean
         */
        public function match($url_array, &$output_args, &$output_attrs, &$url_array_index) {
            // Definição de parâmetros.
            $url_array_index = 0;
            $output_args     = [];
            $output_attrs    = [];

            // Se não houver uma definição, é uma rota de túnel.
            // Sempre deverá ser executada.
            if(!$this->definition) {
                return true;
            }

            // Armazena a compilação da definição.
            $definition = $this->get_compiled();

            // Verifica cada parte da definição.
            $definition_index  = 0;
            $definition_length = count($definition);
            $url_array_length  = count($url_array);

            // Armazena a captura atual de dados.
            $output_value = null;

            // Armazena o número de vezes que uma mesma parte da URL foi capturada.
            $url_array_matched = 0;

            // Enquanto o index da compilação for menor que o seu tamanho,
            // verifica se a URL será valiada.
            while($definition_index < $definition_length) {
                // Carrega as instruções do index atual da definição.
                $definition_part   = $definition[$definition_index];
                $definition_static = is_string($definition_part);

                // Carrega a parte que será testada.
                $url_array_part   = isset($url_array[$url_array_index])
                                        ? $url_array[$url_array_index]
                                        : null;

                // Se a informação for estática, verifica se a parte é o mesmo que o valor.
                if($definition_static) {
                    // Verifica se a definição é opcional.
                    $definition_optional = substr($definition_part, -1) === "?";
                    if($definition_optional) {
                        $definition_part = substr($definition_part, 0, -1);
                    }

                    // Se a parte foi encontrada, avança no index.
                    if($definition_part === $url_array_part) {
                        $url_array_index++;
                    }

                    // Verifica se a definição foi encontrada,
                    // ou se é opcional.
                    if($definition_part === $url_array_part
                    || $definition_optional) {
                        $url_array_matched = 0;
                        $definition_index++;
                        continue;
                    }

                    return false;
                }
                else
                // Se for a primeira parte a ser testada, define o tipo de coleta de saída.
                // Isto dependerá se a definição pode repetir ou não.
                if($url_array_matched === 0) {
                    $output_value = $definition_part->repeat === true
                                        ? []
                                        : null;
                }

                // Caso contrário, identifica que é um objeto Syntax.
                // Neste caso, é necessário iniciar o tipo esperado.
                $type_instance = $definition_part->get_type();

                // Se o tipo carregado puder processar a URL, então valida.
                if($type_instance->validate($url_array_part)) {
                    $url_array_matched++;
                    $url_array_index++;

                    // Processa o valor capturado pelo tipo.
                    $definition_match_value = $type_instance->transform($url_array_part);
                    if($definition_part->repeat === true) {
                        $output_value[] = $definition_match_value;
                    }
                    else {
                        $output_value = $definition_match_value;
                    }

                    // Se for necessário repetir a ação, avança.
                    // Também é necessário respeitar os limites máximos de repetição.
                    if($definition_part->repeat === true) {
                        if($definition_part->repeat_max === null
                        || $url_array_matched < $definition_part->repeat_max) {
                            continue;
                        }
                    }
                }

                // Se houver repetição e o número mínimo de atributos não foi atingido, retorna false.
                if($definition_part->repeat === true
                && $url_array_matched < $definition_part->repeat_min) {
                    if($definition_part->optional !== true) {
                        return false;
                    }

                    // Entretanto, se for opcional, apenas volta alguns passos do processo
                    // e limpa o buffer de saída.
                    $url_array_index-= count($output_value);
                    $output_value = [];
                }

                // Se ao menos uma parte foi validada, ou a parte é opcional,
                // ou é possível zero repetição, então avança.
                if($url_array_matched !== 0
                || $definition_part->optional === true
                || $definition_part->repeat_min === 0) {
                    $definition_index++;
                    $url_array_matched = 0;

                    // Armazena o valor processado.
                    $output_args[] = $output_value;

                    // Se for necessário, define também como um atributo.
                    if($definition_part->name) {
                        $output_attrs[$definition_part->name] = $output_value;
                    }

                    continue;
                }

                // Falha o teste se todas as verificações também falharem.
                return false;
            }

            // Se em nenhum momento a rota foi invalidada, retornará sucesso.
            return true;
        }

        /**
         * Define a rota.
         * @param string $definition Definição da rota.
         */
        public function set_definition($definition) {
            $this->definition = $definition;
        }

        /**
         * Define a callback.
         * @param callable $callback Definição do callable.
         */
        public function set_callback($callback) {
            $this->callback = $callback;
        }

        /**
         * Retorna a callback armazenada.
         * @return callable
         */
        public function get_callback() {
            return $this->callback;
        }

        /**
         * Define se é uma rota de arquivo.
         * @param boolean $is_file Se é um arquivo.
         */
        public function set_file_mode($is_file = null) {
            $this->is_file = $is_file !== false;
        }

        /**
         * Retorna se é uma rota de arquivo.
         * @return boolean
         */
        public function get_file_mode() {
            return $this->is_file;
        }
    }
