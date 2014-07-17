<?php

	// Responsável por gerir a conexão com o banco de dados via PDO.
	class awk_database extends awk_module_base {
		static protected $feature_type = "database";

		// Armazena a instãncia da conexão.
		// @type PDO;
		private $connection;

		// Armazena os dados da conexão.
		// @type array<string, mixed>;
		private $connection_options;

		// Obtém a instância da conexão.
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
			$connection_args = array_intersect_key($connection_options, [ "host", "port", "dbname", "charset" ]);
			$connection_args_string = http_build_query($connection_args, null, ";");

			// Define o DSN que será usado.
			$connection_dsn = "mysql:{$connection_args_string}";

			// Inicia a conexão e retorna.
			return $this->connection = new PDO($connection_dsn, $connection_user, $connection_password, [
				PDO::ATTR_PERSISTENT => $connection_options["persistent"]
			]);
		}

		// Configura a conexão.
		public function configure($connection_options = null) {
			// Define as opções padrões para uma conexão.
			$connection_options = $connection_options ?: [];
			$this->connection_options = array_replace([
				// Host que será conectado.
				// @type string;
				"host" => "127.0.0.1",

				// Porta que será utilizada no host.
				// @type int;
				"port" => 3307,

				// Usuário a ser utilizado na conexão.
				// @type string;
				"user" => "root",

				// Senha a ser utilizada.
				// @type string;
				"password" => null,

				// Database ao conectar.
				// @type string;
				"dbname" => null,

				// Charset padrão.
				// @type string;
				"charset" => "UTF8",

				// Se haverá persistência na conexão.
				// @type boolean;
				"persistent" => true
			], $connection_options);
		}

		// Executa uma query na conexão.
		public function query($query) {
			return $this->get_connection()->query($query);
		}
	}
