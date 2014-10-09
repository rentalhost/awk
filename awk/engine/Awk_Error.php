<?php

	/**
	 * Responsável pelo controle de erros.
	 */
	class Awk_Error {
		// Indica um erro do tipo E_USER_ERROR.
		const TYPE_FATAL = "fatal";
		// Indica um erro do tipo E_USER_WARNING.
		const TYPE_WARNING = "warning";
		// Indica um erro lançado por uma exceção.
		const TYPE_EXCEPTION = "exception";

		/**
		 * Cria um erro com as especificações fornecidas.
		 * @param  mixed[] $error_options Definições do erro.
		 */
		static public function create($error_options) {
			// Definições padrões de um erro.
			$error_options = array_replace([
				// Tipo do erro a ser lançado.
				// @type const TYPE;
				"type" => self::TYPE_EXCEPTION,

				// Tipo de exceção que será lançada.
				// @type string;
				"exception" => "Awk_Exception",

				// Mensagem do erro.
				// @type string?;
				"message" => null,

				// Código do erro.
				// @type int?;
				"code" => null,
			], $error_options);

			// Determina o tipo da execução do erro.
			switch($error_options["type"]) {
				// Lança uma exceção.
				case self::TYPE_EXCEPTION:
					$exception_classname = $error_options["exception"];
					throw new $exception_classname($error_options["message"], $error_options["code"]);
					break;

				// Lança um erro fatal via E_USER_ERROR.
				case self::TYPE_FATAL:
					// Depuração artificial.
					if(defined("UNIT_TESTING")) {
						throw new Exception("OK");
					}

					// @codeCoverageIgnoreStart
					trigger_error($error_options["message"], E_USER_ERROR);
					break;
					// @codeCoverageIgnoreEnd

				// Lança um erro não-fatal via E_USER_WARNING.
				case self::TYPE_WARNING:
					// Depuração artificial.
					if(defined("UNIT_TESTING")) {
						throw new Exception("OK");
					}

					// @codeCoverageIgnoreStart
					trigger_error($error_options["message"], E_USER_WARNING);
					break;
					// @codeCoverageIgnoreEnd
			}

			// Se não for possível, indica um erro desconhecido.
			self::create([
				"type" => self::TYPE_EXCEPTION,
				"message" => "Um erro do tipo \"{$error_options["type"]}\" foi criado, porém, este tipo não é suportado."
			]);
		} // @codeCoverageIgnore

		/**
		 * Força um erro de objeto não encontrado.
		 */
		static public function force_404() {
			$location_error = dirname($_SERVER["SCRIPT_NAME"]) . "/404";
			header("Location: {$location_error}");

			// Depuração artificial.
			if(defined("UNIT_TESTING")) {
				throw new Exception($location_error);
			}
		} // @codeCoverageIgnore
	}
