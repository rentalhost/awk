<?php

    /**
     * Responsável pela definição e gerenciamento de caminhos de objetos como arquivos e diretórios.
     */
    class Awk_Path {
        /**
         * Armazena o caminho.
         * @var string
         */
        private $path;

        /**
         * Constrói uma nova instância.
         * @param string $path Caminho do objeto.
         */
        public function __construct($path) {
            $this->path = $path;
        }

        /**
         * Retorna se o objeto é um arquivo.
         * @return boolean
         */
        public function is_file() {
            return is_file($this->path);
        }

        /**
         * Retorna se o objeto é um diretório.
         * @return boolean
         */
        public function is_dir() {
            return is_dir($this->path);
        }

        /**
         * Retorna se o objeto é legível.
         * @return boolean
         */
        public function is_readable() {
            return is_readable($this->path);
        }

        /**
         * Obtém o caminho armazenado no objeto.
         * A informação é recebida sem normalização.
         * @return string
         */
        public function get() {
            return $this->path;
        }

        /**
         * Retorna o caminho do objeto normalizado.
         * @return string
         */
        public function get_normalized() {
            return self::normalize($this->path);
        }

        /**
         * Retorna o nome base do objeto.
         * @return string
         */
        public function get_basename() {
            return basename($this->path);
        }

        /**
         * Retorna o diretório do objeto.
         * @return string
         */
        public function get_dirname() {
            return dirname($this->path);
        }

        /**
         * Retorna a extensão do objeto.
         * Retornará null se o objeto for um diretório.
         * @return string|null
         */
        public function get_extension() {
            if(is_file($this->path)) {
                return pathinfo($this->path, PATHINFO_EXTENSION);
            }
        }

        /**
         * Retorna se um objeto existe.
         * @return boolean
         */
        public function exists() {
            return is_readable($this->path);
        }

        /**
         * Normaliza um caminho absoluto, mesmo quando não houver um objeto real atingível.
         * @param  string $path Caminho a ser normalizado.
         * @return string
         */
        static private function normalize_unreal($path) {
            $original_parts = preg_split("/[\\\\\\/]+/", $path);

            // Dados do resultado.
            $result_parts = [];
            $result_offset = 0;

            // Indica que há uma barra inicial.
            $result_slash = count($original_parts) > 0 && $original_parts[0] === "";
            if($result_slash) {
                array_shift($original_parts);
            }

            // Valida cada parte do caminho.
            foreach($original_parts as $original_offset => $original_part) {
                // Se for [dot], basta ignorar.
                if($original_part === ".") {
                    continue;
                }
                else
                // Se for [dot][dot] e houver offset válido, então remove e avança.
                if($original_part === ".."
                && $result_offset > 0) {
                    array_pop($result_parts);
                    $result_offset--;
                    continue;
                }

                // Em último caso, adiciona a parte.
                $result_parts[] = $original_part;

                // Avança o offset válido, apenas quando não for [dot][dot].
                $result_offset+= $original_part !== "..";
            }

            // Armazena o resultado.
            $result = trim(join("/", $result_parts), "/");

            // Adiciona a barra inicial, se houver.
            if($result_slash) {
                $result = "/" . $result;
            }

            // Retorna o resultado.
            return $result;
        }

        /**
         * Normaliza um caminho de arquivo ou pasta.
         * @param  string $path Caminho a ser normalizado.
         * @return string
         */
        static public function normalize($path) {
            // Se o caminho real existir, executará o processo através de um `php:preg_replace()`.
            // Caso contrário, será necessário a utilização do método de normalização de *irreais*.
            $realpath = realpath($path);
            if($realpath) {
                return preg_replace("/[\\\\\\/]+/", "/", $realpath);
            }

            return self::normalize_unreal($path);
        }
    }
