<?php

    /**
     * Responsável por armazenar um objeto de Syntax.
     */
    class Awk_Syntax_Object {
        /**
         * Valida uma definição de objeto.
         * @var string
         */
        const REGEX_OBJECT_MATCH = "/^

            # Valida os detalhes de um objeto.
            :{(?<object>.+?)}

            # Valida a definição de repetidores e flag.
            (?:
                (?:
                    # Valida o alcance de repetição.
                    (?<repeat_range>{
                        (?<repeat_min>\d+)?
                        (?<repeat_separator>,)?
                        (?<repeat_max>\d+)?
                    })

                    |

                    # Valida a definição da flag.
                    (?<repeat_flag>
                        [+*]
                    )
                )?

                # Valida a flag de opcional.
                (?<optional_flag>
                    [?]
                )?
            )?

        $/x";

        /**
         * Armazena o módulo do contexto.
         * @var Awk_Module
         */
        public $module;

        /**
         * Armazena o método.
         * @example :method{} => method
         * @var     string
         */
        public $method;

        /**
         * Armazena o tipo de método.
         * @example :name   => statement
         * @example :name{} => method
         * @var     string
         */
        public $method_type;

        /**
         * Armazena o módulo do método.
         * O valor é uma string enquando não for convertido,
         *     e se transforma em um Awk_Module após a primeira execução.
         * A string "module" remete ao próprio módulo de contexto.
         * O valor "null" remete aos recursos do framework.
         * @example :module->method{} => module
         * @var     Awk_Module|string|null
         */
        public $method_module;

        /**
         * Armazena o nome do objeto.
         * @example {@name} => name
         * @example {this}  => this
         * @var     string
         */
        public $name;

        /**
         * Armazena o tipo do nome do objeto.
         * @example {this}  => object
         * @example {@this} => variable
         * @var     string
         */
        public $name_type;

        /**
         * Armazena um grupo anexo ao nome.
         * @example {this#1} => 1
         * @var     string
         */
        public $name_group;

        /**
         * Armazena o aliases para o nome do objeto.
         * @example {@name as alias} => alias
         * @var     string
         */
        public $name_alias;

        /**
         * Armazena o tipo do objeto.
         * @example {type @name} => type
         * @var     string
         */
        public $type;

        /**
         * Armazena o módulo do tipo.
         * O valor é uma string enquando não for convertido,
         *     e se transforma em um Awk_Module após a primeira execução.
         * A string "module" remete ao próprio módulo de contexto.
         * O valor "null" remete aos recursos do framework.
         * @example {module->date @name} => module
         * @var     Awk_Module|string|null
         */
        public $type_module;

        /**
         * Indicativo de repetição.
         * @var boolean
         */
        public $repeat;

        /**
         * Indicativo de repetição mínima.
         * @var int
         */
        public $repeat_min;

        /**
         * Indicativo de repetição máxima.
         * @var int
         */
        public $repeat_max;

        /**
         * Indicativo de opcional.
         * @var boolean
         */
        public $optional;

        /**
         * Armazena os argumentos do objeto.
         * @example ::Hello World!      => Hello World!
         * @example :method{1, 2, 3}    => mixed[3] { 1, 2, 3 }
         * @example {this: id, name}    => self[2] { "id", "name" }
         * @example :statement "Hello"  => "Hello"
         * @var     self[]|string
         */
        public $arguments;

        /**
         * Constrói um novo objeto com base em sua definição.
         * @param   Awk_Module  $module     Módulo de contexto.
         * @param   string      $definition Definição do objeto.
         * @param   mixed[]     $options    Opções de identificação.
         * @return  self
         */
        static public function create($module, $definition, $options = null) {
            $object_instance = new self;
            $object_instance->module = $module;

            // Preenche as opções padrões.
            $options = array_replace([
                /**
                 * Indica se validará comentários.
                 * @var boolean
                 */
                "validate_comment" => null,

                /**
                 * Indica se validará métodos.
                 * @var boolean
                 */
                "validate_method" => null,

                /**
                 * Indica se validará statements.
                 * @var boolean
                 */
                "validate_statement" => null,

                /**
                 * Indica se validará uma variável.
                 * @var boolean
                 */
                "validate_variable" => null,

                /**
                 * Indica se validará um objeto.
                 * @var boolean
                 */
                "validate_object" => null,
            ], $options);

            // Identifica se é um comentário.
            if($options["validate_comment"] !== false
            && self::create_comment($object_instance, $definition)) {
                return $object_instance;
            }

            // Identifica se é um statement ou método.
            if($options["validate_statement"] !== false
            || $options["validate_method"] !== false) {
                if(self::create_method($object_instance, $definition, $options)) {
                    return $object_instance;
                }
            }

            // Identifica se é um objeto encapsulado, como uma variável.
            if($options["validate_variable"] !== false
            || $options["validate_object"] !== false) {
                if(preg_match(self::REGEX_OBJECT_MATCH, $definition, $definition_match)
                && self::create_object($object_instance, $definition_match, $options)) {
                    return $object_instance;
                }
            }

            // Se nada foi identificado, lança uma exceção.
            throw new Awk_Syntax_UnsupportedFormat_Exception($definition);
        }

        /**
         * Cria um comentário.
         * @param  self   $object_instance  Instância a ser preenchida.
         * @param  string $definition       Definição a ser reconhecida.
         * @return true|null
         */
        static private function create_comment($object_instance, $definition) {
            if(substr($definition, 0, 2) === "::") {
                $object_instance->method    = "comment";
                $object_instance->arguments = self::group_contents(substr($definition, 2));

                // Validação com sucesso.
                return true;
            }
        }

        /**
         * Cria um método ou statement.
         * @param  self    $object_instance Instância a ser preenchida.
         * @param  string  $definition      Definição a ser reconhecida.
         * @param  mixed[] $options         Opções de identificação.
         * @return true|null
         */
        static private function create_method($object_instance, $definition, $options) {
            // Identificador de statements.
            static $definition_expression = "/^

                # Identifica a chave.
                :

                # Identifica o módulo, se houver.
                (?:
                    (?<method_module>\w+)
                    ->
                )?

                # Identifica um statement.
                (?<method>\w+)

                # Identifica argumentos.
                (?<arguments>.*)

            $/x";

            // Executa a identificação do statement.
            if(preg_match($definition_expression, $definition, $definition_match)) {
                // Define o nome do método.
                $object_instance->method = $definition_match["method"];

                // Define o módulo do método.
                if(!empty($definition_match["method_module"])) {
                    $object_instance->method_module = $definition_match["method_module"];
                }

                // Se não houver argumentos, é automaticamente um statement.
                if(empty($definition_match["arguments"])) {
                    $object_instance->method_type = "statement";

                    // Validação com sucesso.
                    return true;
                }
                else
                // Verifica se é possível complementar os argumentos como método.
                if(self::complement_method_arguments($object_instance, $definition_match["arguments"], $options)) {
                    $object_instance->method_type = "method";

                    // Validação com sucesso.
                    return true;
                }
                else
                // Caso contrário, define como argumentos de um statement.
                if(preg_match("/^\s(.+)$/", $definition_match["arguments"], $arguments_match)) {
                    $object_instance->method_type = "statement";
                    $object_instance->arguments   = $arguments_match[1];

                    // Validação com sucesso.
                    return true;
                }
            }
        }

        /**
         * Complementa os argumentos de um método.
         * @param  self    $object_instance Instância a ser preenchida.
         * @param  string  $definition      Definição a ser reconhecida.
         * @param  mixed[] $options         Opções de identificação.
         * @return true|null
         */
        static private function complement_method_arguments($object_instance, $definition, $options) {
            // Identificador de métodos.
            static $definition_expression = "/^
                {
                    # Identifica os argumentos do método.
                    (?<method_arguments>.*)
                }
            $/x";

            // Falha se não puder identificar argumentos.
            if(!preg_match($definition_expression, $definition, $definition_match)) {
                return;
            }

            // Caso contrário, redefine.
            $definition = $definition_match["method_arguments"];

            // Se a lista estiver vazia, valida imediatamente.
            if(empty($definition)) {
                $object_instance->arguments = [];
                return true;
            }

            // Obtém os blocos de argumentos, se não for possível, invalida.
            if(!self::split_arguments($object_instance, $definition, $options)) {
                return;
            }

            // Validação com sucesso.
            return true;
        }

        /**
         * Separa os argumentos da definição.
         * @param  self    $object_instance Instância a ser preenchida.
         * @param  string  $definition      Definição a ser reconhecida.
         * @param  mixed[] $options         Opções de identificação.
         * @return string[]
         */
        static private function split_arguments($object_instance, $definition, $options) {
            // Armazena as informações obtidas.
            $definition_value  = null;
            $definition_index  = 0;
            $definition_length = strlen($definition);

            // Captura de sequencia de caracteres.
            $capture_string = false;

            // Informações de profundidade.
            $depth_expected_index   = 0;
            $depth_expected_stack   = [ null ];
            $depth_expected_current = null;

            // Inicia o processamento de argumentos.
            while($definition_index < $definition_length) {
                $definition_char = $definition[$definition_index];

                // Identifica o final de uma sequencia de caracteres.
                if($definition_char === $capture_string) {
                    $capture_string = false;
                }
                else
                // Se não estiver dentro de uma sequencia de caracteres...
                if($capture_string === false) {
                    // Identifica uma sequencia de caracteres.
                    if($definition_char === "\""
                    || $definition_char === "'"
                    || $definition_char === "`") {
                        $capture_string = $definition_char;
                    }
                    else
                    // Identifica o final de um encapsulador.
                    if($definition_char === $depth_expected_current) {
                        array_pop($depth_expected_stack);
                        $depth_expected_index--;
                        $depth_expected_current = $depth_expected_stack[$depth_expected_index];
                    }
                    else
                    // Identifica o início de um encapsulador.
                    if($definition_char === "("
                    || $definition_char === "{"
                    || $definition_char === "[") {
                        $depth_expected_index++;

                        // Prepara o elemento que fechará.
                        if($definition_char === "(") {
                            $depth_expected_stack[] = ")";
                        }
                        else
                        if($definition_char === "{") {
                            $depth_expected_stack[] = "}";
                        }
                        else
                        if($definition_char === "[") {
                            $depth_expected_stack[] = "]";
                        }

                        $depth_expected_current = $depth_expected_stack[$depth_expected_index];
                    }
                    else
                    // Se a profundidade for zero e encontrar uma vírgula, separa os argumentos.
                    if($depth_expected_index === 0
                    && $definition_char === ",") {
                        // Falhará se não foi coletado informação.
                        if(!self::add_argument($object_instance, $definition_value)) {
                            return;
                        }

                        // Avança para o próximo caractere.
                        $definition_index++;
                        continue;
                    }
                    else
                    // Se um escape for encontrado...
                    if($depth_expected_index === 0
                    && $definition_char === "\\") {
                        // Falha se não houver um próximo caractere.
                        if(!isset($definition[$definition_index + 1])) {
                            return;
                        }

                        // Armazena o próximo caractere.
                        $definition_char_next = $definition[$definition_index + 1];

                        // Indica se aceitará a definição.
                        // Valida um escape-escape.
                        $definition_escape = $definition_char_next === "\\";

                        // Verifica se será possível escapar uma vírgula.
                        $definition_escape|= $capture_string === false &&
                                             $depth_expected_index === 0 &&
                                             $definition_char_next === ",";

                        // Verifica se será possível escapar encapsuladores.
                        $definition_escape|= $capture_string === false && (
                                                 $definition_char_next === "{" ||
                                                 $definition_char_next === "}"
                                             );

                        // Verifica o escape foi aceito.
                        if($definition_escape) {
                            $definition_value.= $definition_char_next;
                            $definition_index+= 2;
                            continue;
                        }
                    }
                }
                else
                // Se dentro de uma sequencia de caracteres for encontrado um escape,
                // mantém a informação se for escape-escape ou escape-expected.
                if($definition_char === "\\") {
                    // Falha se não houver um próximo caractere.
                    if(!isset($definition[$definition_index + 1])) {
                        return;
                    }

                    // Armazena o próximo caractere.
                    $definition_char_next = $definition[$definition_index + 1];

                    // Verifica o escape foi aceito.
                    if($definition_char_next === "\\"
                    || $definition_char_next === $capture_string) {
                        $definition_value.= $definition_char . $definition_char_next;
                        $definition_index+= 2;
                        continue;
                    }
                }

                // Adiciona o caractere e avança.
                $definition_value.= $definition_char;
                $definition_index++;
            }

            // A profundidade final deve ser zero,
            // ou uma string deve ser declarada por completo,
            // caso contrário, falhará.
            if($depth_expected_index !== 0
            || $capture_string !== false) {
                return;
            }

            // Se houver um valor sobrando, adiciona como argumento.
            // Falhará se o último argumento estiver vazio.
            if(!self::add_argument($object_instance, $definition_value)) {
                return;
            }

            // Validação com sucesso.
            return true;
        }

        /**
         * Adiciona um argumento identificado.
         * @param  self    $object_instance     Instância a ser preenchida.
         * @param  string  $definition_value    Definição reconhecida.
         */
        static private function add_argument($object_instance, &$definition_value) {
            // Apara a informação recebida.
            $definition_trimmed = trim($definition_value);

            // Falhará se o valor, após trimmed, estiver vazio.
            if(empty($definition_trimmed)) {
                return;
            }

            // Adiciona o argumento.
            $object_instance->arguments[] = $definition_trimmed;
            $definition_value = null;

            // Incluído com sucesso.
            return true;
        }

        /**
         * Cria um objeto ou variável.
         * @param  self     $object_instance    Instância a ser preenchida.
         * @param  string[] $definition_match   Definição a ser reconhecida.
         * @param  mixed[]  $options            Opções de identificação.
         * @return true|null
         */
        static private function create_object($object_instance, $definition_match, $options) {
            // Identificador de objetos.
            static $definition_expression = "/^

                # Identifica o tipo.
                (?:
                    # Identifica o módulo, se houver.
                    (?:
                        (?<type_module>\w+)
                        ->
                    )?

                    # Identifica o tipo.
                    (?<type>\w+)

                    # Após o tipo, é necessário espaçamento(s).
                    \s+
                )?

                # Identifica se o objeto é uma variável.
                (?<name_variable_identifier>@)?

                # Identifica o nome do objeto.
                (?<name>\w+)

                # Identifica um grupo.
                (?:
                    \#
                    (?<name_group>\d+)
                )?

                # Identifica um conteúdo de contexto sql.
                (?:
                    \s*

                    # Identifica o separador.
                    (?<sql_contents_type>[:\.])

                    # Identifica o conteúdo.
                    (?<sql_contents>.+)
                )?

                # Identifica um alias.
                (?:
                    # Antes é necessário o indicador AS.
                    \s+ [Aa][Ss] \s+

                    # Identifica um arroba.
                    @

                    # Identifica a informação.
                    (?<name_alias>\w+)
                )?

            $/x";

            // Valida o complemento de repetição.
            if(!self::complement_repetition($object_instance, $definition_match, $options)) {
                return;
            }

            // Executa a identificação do objeto.
            if(preg_match($definition_expression, $definition_match["object"], $definition_match)) {
                // Armazena o tipo.
                if(!empty($definition_match["type"])) {
                    $object_instance->type = $definition_match["type"];

                    // Armazena o módulo do tipo.
                    if(!empty($definition_match["type_module"])) {
                        $object_instance->type_module = $definition_match["type_module"];
                    }
                }

                // Indica o tipo do objeto encontrado.
                $object_instance->name_type = empty($definition_match["name_variable_identifier"]) ? "object" : "variable";

                // Armazena o nome do objeto.
                $object_instance->name = $definition_match["name"];

                // Armazena o grupo do objeto.
                if(!empty($definition_match["name_group"])) {
                    $object_instance->name_group = $definition_match["name_group"];
                }

                // Identifica um conteúdo aplicado ao contexto sql.
                if(!empty($definition_match["sql_contents_type"])) {
                    return self::complement_object_sql($object_instance, $definition_match["sql_contents"], $definition_match["sql_contents_type"], $options);
                }

                // Armazena o alias do objeto.
                if(!empty($definition_match["name_alias"])) {
                    $object_instance->name_alias = $definition_match["name_alias"];
                }

                // Validação com sucesso.
                return true;
            }
        }

        /**
         * Complementa o conteúdo adicional de um objeto.
         * @param  self    $object_instance Instância a ser preenchida.
         * @param  string  $definition      Definição a ser reconhecida.
         * @param  string  $definition_type Definição do tipo de separador.
         * @param  mixed[] $options         Opções de identificação.
         * @return true|null
         */
        static private function complement_object_sql($object_instance, $definition, $definition_type, $options) {
            // Identificador do complemento.
            static $definition_expression = "/

                \s*

                # Identifica o tipo.
                (?:
                    # Identifica o módulo, se houver.
                    (?:
                        (?<type_module>\w+)
                        ->
                    )?

                    # Identifica o tipo.
                    (?<type>\w+)

                    # Após o tipo, é necessário espaçamento(s).
                    \s+
                )?

                # Identifica o nome do objeto.
                (?<name>\w+|\*)

                # Identifica um alias.
                (?:
                    # Antes é necessário o indicador AS.
                    \s+ [Aa][Ss] \s+

                    # Identifica a informação.
                    (?<name_alias>\w+)
                )?

                # Espera o final do arquivo, ou uma continuação.
                \s*
                (,|$)

            /x";

            // Armazena o tamanho dos itens coletados.
            $match_length = 0;

            // Executa a identificação do complemento.
            if(preg_match_all($definition_expression, $definition, $definition_matches, PREG_SET_ORDER)) {
                // Se o tipo de objeto esperar apenas um único elemento e houver mais, invalida.
                if($definition_type === "."
                && count($definition_matches) !== 1) {
                    return;
                }

                // Armazena os argumentos capturados.
                $object_instance->arguments = [];

                // Preenche os argumentos identificados.
                foreach($definition_matches as $definition_match) {
                    // Aumenta o tamanho coletado.
                    $match_length+= strlen($definition_match[0]);

                    // Gera o objeto do argumento.
                    $argument_instance = new self;
                    $argument_instance->module = $object_instance->module;
                    $argument_instance->name_type = "object";

                    // Armazena o tipo.
                    if(!empty($definition_match["type"])) {
                        $argument_instance->type = $definition_match["type"];

                        // Armazena o módulo do tipo.
                        if(!empty($definition_match["type_module"])) {
                            $argument_instance->type_module = $definition_match["type_module"];
                        }
                    }

                    // Armazena o nome do objeto.
                    $argument_instance->name = $definition_match["name"];

                    // Armazena o alias do objeto.
                    if(!empty($definition_match["name_alias"])) {
                        $argument_instance->name_alias = $definition_match["name_alias"];
                    }

                    // Adiciona a informação aos argumentos.
                    $object_instance->arguments[] = $argument_instance;
                }
            }

            // Validação será um sucesso se o tamanho coletado for igual ao tamanho da definição.
            return strlen($definition) === $match_length;
        }

        /**
         * Complementa o conteúdo com informações de repetidores.
         * @param  self     $object_instance  Instância a ser preenchida.
         * @param  string[] $definition_match Definição a ser reconhecida.
         * @param  mixed[]  $options          Opções de identificação.
         * @return true|null
         */
        static private function complement_repetition($object_instance, $definition_match, $options) {
            // Invalida um repeat range incorreto.
            if(!empty($definition_match["repeat_range"])) {
                if($definition_match["repeat_range"] === "{}"
                || $definition_match["repeat_range"] === "{,}") {
                    return;
                }
            }

            // Define a flag de repetição.
            if(!empty($definition_match["repeat_flag"])) {
                $object_instance->repeat = true;

                // Se for zero+.
                if($definition_match["repeat_flag"] === "*") {
                    $object_instance->repeat_min = 0;
                }
                else
                // Se for one+.
                if($definition_match["repeat_flag"] === "+") {
                    $object_instance->repeat_min = 1;
                }
            }
            else {
                // Define a repetição mínima, se houver.
                if(!empty($definition_match["repeat_min"])) {
                    $object_instance->repeat = true;
                    $object_instance->repeat_min = intval($definition_match["repeat_min"]);

                    // Se não foi definido uma repetição máxima, define como a mínima.
                    // Por exemplo: {3} => 3..3
                    if(empty($definition_match["repeat_max"])
                    && empty($definition_match["repeat_separator"])) {
                        $object_instance->repeat_max = $object_instance->repeat_min;
                    }
                }

                // Define a repetição máxima, se houver.
                if(!empty($definition_match["repeat_max"])) {
                    $object_instance->repeat = true;
                    $object_instance->repeat_max = intval($definition_match["repeat_max"]);
                }
            }

            // Define a flag opcional.
            if(!empty($definition_match["optional_flag"])) {
                $object_instance->optional = true;
            }

            // Validação com sucesso.
            return true;
        }

        /**
         * Agrupa um conteúdo quebrado por contra-barra em fim de linha.
         * @param  string $definition Definição a ser agrupada.
         * @return string
         */
        static private function group_contents($definition) {
            return join(preg_split("/\\\s*\r?\n\s*/", $definition));
        }
    }
