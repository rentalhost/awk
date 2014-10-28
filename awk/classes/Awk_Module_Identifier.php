<?php

    /**
     * Responsável por armazenar uma instância de um identificador.
     */
    class Awk_Module_Identifier {
        /**
         * Armazena a instância da feature identificada.
         * @var object
         */
        public $feature;

        /**
         * Armazena o módulo responsável pela identificação.
         * @var Awk_Module
         */
        public $module;

        /**
         * Armazena o nome do objeto identificado.
         * @var string
         */
        public $name;

        /**
         * Armazena o método identificado.
         * @var string
         */
        public $method;

        /**
         * Indica se o recurso foi bloqueado.
         * @var boolean
         */
        private $feature_blocked = false;

        /**
         * Indica se o módulo é obrigatório.
         * @var boolean
         */
        private $module_required = false;

        /**
         * Identifica uma string e retorna a instância.
         * @param  string $id Informação que será identificada.
         * @throws Awk_Module_IdRequiresModule_Exception
         *         Caso o identificador não tenha definido um módulo obrigatório.
         * @throws Awk_Module_IdRequiresFeature_Exception
         *         Caso o identificador não tenha definido um recurso obrigatório.
         * @throws Awk_Module_IdFeatureExpected_Exception
         *         Caso o identificador tenha definido um recurso diferente do esperado.
         * @throws Awk_Module_IdUnsupportedFormat_Exception
         *         Caso o identificador tenha sido definido em um formato não suportado.
         * @return self
         */
        public function identify($id) {
            // Executa a tarefa de identificação, separando cada parte.
            $id_validate = preg_match("/^
                (?<feature>\w+\@)?
                (?<module>\w+\-\>)?
                (?<name>[\w\/\.]+)
                (?<method>::\w+)?
            $/x", $id, $id_match);

            if($id_validate) {
                // Módulo que será utilizado.
                if(!empty($id_match["module"])) {
                    $this->module = Awk_Module::get(substr($id_match["module"], 0, -2));
                }
                else
                // Se o módulo for obrigatório, mas nenhum foi explicitamente informado,
                // lança uma exceção.
                if($this->module_required === true) {
                    throw new Awk_Module_IdRequiresModule_Exception($id);
                }

                // Define a feature a ser utilizada.
                // Se uma feature não foi previamente definida, espera que tenha a feito agora,
                // caso contrário, lançará uma exceção.
                if($this->feature === null
                && empty($id_match["feature"])) {
                    throw new Awk_Module_IdRequiresFeature_Exception($id);
                }
                else
                // Se um feature foi definido...
                if(!empty($id_match["feature"])) {
                    $feature_type = substr($id_match["feature"], 0, -1);

                    // Se houve um bloqueio de feature,
                    // espera-se que não seja diferente da feature previamente definida,
                    // caso contrário, será lançado uma exceção.
                    if($this->feature_blocked === true
                    && $this->feature !== $feature_type) {
                        throw new Awk_Module_IdFeatureExpected_Exception($id, $this->feature);
                    }

                    // Se tudo ocorreu bem, aplica a feature na instância.
                    $this->feature = $feature_type;
                }

                // Armazena o nome informado.
                $this->name = $id_match["name"];

                // Identifica um método.
                $this->method = null;
                if(!empty($id_match["method"])) {
                    $this->method = substr($id_match["method"], 2);
                }

                // Se tudo ocorreu bem, retorna a instância.
                return $this;
            }

            // Se não foi possível validar, lança uma exceção.
            throw new Awk_Module_IdUnsupportedFormat_Exception($id);
        }

        /**
         * Indica que o recurso será bloqueado.
         * @param boolean $mode Se o recurso será bloqueado.
         */
        public function set_feature_blocked($mode = null) {
            $this->feature_blocked = $mode !== false;
        }

        /**
         * Indica que o módulo é obrigatório.
         * @param boolean $mode Se o módulo é obrigatório.
         */
        public function set_module_required($mode = null) {
            $this->module_required = $mode !== false;
        }

        /**
         * Retorna uma instância com base nas informações identificadas.
         * @return object
         */
        public function get_instance() {
            // Se a feature for View, impede que ela seja processada automaticamente.
            $instance_arguments = $this->feature === "view"
                ? [ $this->name, null, null, true ]
                : [ $this->name ];

            return $this->module->__call($this->feature, $instance_arguments);
        }

        /**
         * Retorna um callable com base no identificador.
         * @return callable
         */
        public function get_callable() {
            $identifier_instance = $this->get_instance();

            return [ $identifier_instance, $this->method ];
        }
    }
