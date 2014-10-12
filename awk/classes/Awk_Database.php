<?php

    /**
     * Responsável por gerir a conexão com o banco de dados via PDO.
     */
    class Awk_Database extends Awk_Module_Base {
        /**
         * Define o tipo de recurso.
         * @var string
         */
        static protected $feature_type = "database";

        /**
         * Determina o nome da instância.
         * Atualmente, todas as instâncias são chamadas de "default".
         * @var string
         */
        protected $name = "default";

        /**
         * Armazena a instãncia da conexão.
         * @var PDO
         */
        private $connection;

        /**
         * Armazena os dados da conexão.
         * @var mixed[]
         */
        private $connection_options;

        /**
         * Obtém a instância da conexão.
         * @return PDO
         */
        private function get_connection() {
            // Se a conexão já foi iniciada, retorna.
            // Caso contrário será necessário iniciá-la.
            if($this->connection) {
                return $this->connection;
            }

            // Copia os dados da configuração.
            $connection_options = $this->connection_options;
            $connection_user = $connection_options["user"];
            $connection_password = $connection_options["password"];

            // Define os argumentos da conexão (DSN).
            $connection_args = array_intersect_key($connection_options, array_flip([ "host", "port", "dbname", "charset" ]));
            $connection_args_string = http_build_query($connection_args, null, ";");

            // Define o DSN que será usado.
            $connection_dsn = "mysql:{$connection_args_string}";

            // Inicia a conexão e retorna.
            return $this->connection = new PDO($connection_dsn, $connection_user, $connection_password, [
                PDO::ATTR_PERSISTENT => $connection_options["persistent"]
            ]);
        }

        /**
         * Força a conexão.
         * @return boolean Indica se a conexão foi bem-sucedida.
         */
        public function connect() {
            try {
                $this->get_connection();
                return $this->connection->errorCode() == 0;
            }
            catch(PDOException $e) {
                return false;
            }
        }

        /**
         * Configura a conexão.
         * @param  mixed[] $connection_options Configurações da conexão.
         */
        public function configure($connection_options = null) {
            // Destrói a conexão atual, se houver.
            $this->connection = null;

            // Define as opções padrões para uma conexão.
            $connection_options = $connection_options ?: [];
            $this->connection_options = array_replace([
                /**
                 * Host que será conectado.
                 * @var string
                 */
                "host" => "127.0.0.1",

                /**
                 * Porta que será utilizada no host.
                 * @var integer
                 */
                "port" => 3306,

                /**
                 * Usuário a ser utilizado na conexão.
                 * @var string
                 */
                "user" => "root",

                /**
                 * Senha a ser utilizada.
                 * @var string
                 */
                "password" => null,

                /**
                 * Database ao conectar.
                 * @var string
                 */
                "dbname" => null,

                /**
                 * Charset padrão.
                 * @var string
                 */
                "charset" => "UTF8",

                /**
                 * Se haverá persistência na conexão.
                 * @var boolean
                 */
                "persistent" => true
            ], $connection_options);
        }

        /**
         * Executa uma query na conexão.
         * @param  string        $query Query a ser executada na conexão.
         * @return PDO_Statement        Resposta da query.
         */
        public function query($query) {
            return $this->get_connection()->query($query);
        }
    }
