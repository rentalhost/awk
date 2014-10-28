<?php

    /**
     * Responsável pelo controle da feature type.
     */
    class Awk_Type_Feature extends Awk_Module_Feature implements Awk_FeatureAbstraction_Interface {
        /**
         * Indica se o index de tipos já foi carregado.
         * @var boolean
         */
        private $index_loaded = false;

        /**
         * Armazena as instâncias das types.
         * @var Awk_Type[]
         */
        private $types = [];

        /**
         * Retorna o controle da type.
         * @param  string $type_id Identificador do tipo.
         * @return Awk_Type
         */
        public function load($type_id) {
            $this->load_index();

            // Se já foi registrado, retorna.
            // Caso contrário será necessário carregá-lo.
            if(isset($this->types[$type_id])) {
                return $this->types[$type_id];
            }

            // Carrega e retorna.
            $type_instance = new Awk_Type($this->module, $this);
            $type_instance->load($type_id);

            return $this->types[$type_id] = $type_instance;
        }

        /**
         * Verifica e carrega o index de tipos.
         */
        private function load_index() {
            // Se o index não foi carregado, o faz agora.
            if(!$this->index_loaded) {
                $this->index_loaded = true;
                $this->module->include_clean($this->module->path->get() . "/types/index.php");
            }
        }

        /**
         * Permite criar um tipo diretamente no módulo.
         * A instância do tipo é retornado por este método.
         * @param  string   $type_name          Nome do tipo a ser criado.
         * @param  callable $callback_validate  Define um callable de validação do tipo.
         * @param  callable $callback_transform Define um callable de transformação do tipo.
         * @throws Awk_Type_AlreadyExists_Exception
         *         Caso o tipo informado já exista.
         * @return Awk_Type
         */
        public function create($type_name, $callback_validate = null, $callback_transform = null) {
            // Se o tipo informado já existir, lança uma exceção.
            if(isset($this->types[$type_name])) {
                throw new Awk_Type_AlreadyExists_Exception($this->module, $type_name, $this->types[$type_name]->path->get_normalized());
            }

            // Caso contrário, ele será criado.
            $type_instance = new Awk_Type($this->module, $this);
            $type_instance->name = $type_name;

            // Identifica o path de definição do tipo.
            $type_debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            $type_instance->path = new Awk_Path($type_debug[0]["file"]);

            // Se foi definido um callback de validação, aplica.
            if($callback_validate) {
                $type_instance->set_validate($callback_validate);
            }

            // Se foi definido um callback de transformação, aplica.
            if($callback_transform) {
                $type_instance->set_transform($callback_transform);
            }

            // Salva e retorna o tipo.
            return $this->types[$type_name] = $type_instance;
        }

        /**
         * @see Awk_FeatureAbstraction_Interface::exists()
         */
        public function exists($type_name) {
            $this->load_index();

            // Se o tipo já foi definido, ele existe.
            if(isset($this->types[$type_name])) {
                return true;
            }

            // Caso contrário, espera-se que exista um arquivo com o nome indicado.
            return parent::exists($type_name, "types");
        }
    }
