<?php

    /**
     * Responsável pelo controle de arquivos cache.
     */
    class Awk_Cache extends Awk_Module_Base {
        /**
         * Armazena o hash do valor do objeto.
         * @var string
         */
        public $hash;

        /**
         * Prepara os diretórios de cache.
         */
        private function prepare_directory() {
            // Cria o diretório de cache, se necessário.
            $module_cache_dir = $this->module->path->get() . "/caches";
            if(!is_dir($module_cache_dir)) {
                mkdir($module_cache_dir, 0700);
            }

            // Cria o diretório do objeto.
            $object_cache_dir = $module_cache_dir . "/" . dirname($this->hash);
            if(!is_dir($object_cache_dir)) {
                mkdir($object_cache_dir, 0700);
            }
        }

        /**
         * Carrega a definição do arquivo e retorna.
         * Se um nome de referência não for informado, um nome aleatório é gerado.
         * @param  string $object_name Identificador do arquivo cache.
         */
        public function load($object_name = null) {
            $this->hash = Awk_Cache_Feature::get_object_hash($object_name);
            $this->name = $object_name;
            $this->path = new Awk_Path($this->module->path->get() . "/caches/" . $this->hash);
        }

        /**
         * Armazena uma informação no arquivo.
         * @param  string $object_value Informação que será armazenada.
         */
        public function set($object_data) {
            $this->prepare_directory();
            file_put_contents($this->path->get(), $object_data);
        }

        /**
         * Retorna a informação armazenada no arquivo.
         * @return string|false
         */
        public function get() {
            if(!$this->path->exists()) {
                return false;
            }

            return file_get_contents($this->path->get());
        }

        /**
         * Remove um arquivo de cache.
         */
        public function remove() {
            if($this->path->exists()) {
                unlink($this->path->get());
            }
        }
    }
