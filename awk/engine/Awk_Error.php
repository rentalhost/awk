<?php

	// Responsável pelo controle de erros.
	class Awk_Error {
		/** CONST TYPE */
		// Indica um erro do tipo E_USER_ERROR.
		const TYPE_FATAL = "fatal";
		// Indica um erro do tipo E_USER_WARNING.
		const TYPE_WARNING = "warning";
		// Indica um erro lançado por uma exceção.
		const TYPE_EXCEPTION = "exception";

		// Cria um erro com as especificações fornecidas.
		static public function create($error_options) {
			// Definições padrões de um erro.
			$error_options = array_replace([
				// Tipo do erro a ser lançado.
				// @type const TYPE;
				"type" => self::TYPE_EXCEPTION,

				// Tipo de exceção que será lançada.
				// @type string;
				"exception" => "Awk_Error_Exception",

				// Mensagem do erro.
				// @type string?;
				"message" => null,
			], $error_options);

			// Determina o tipo da execução do erro.
			// @codeCoverageIgnoreStart
			switch($error_options["type"]) {
				// Lança uma exceção.
				case self::TYPE_EXCEPTION:
					$exception_classname = $error_options["exception"];
					throw new $exception_classname($error_options["message"]);
					break;

				// Lança um erro fatal via E_USER_ERROR.
				case self::TYPE_FATAL:
					trigger_error($error_options["message"], E_USER_ERROR);
					break;

				// Lança um erro não-fatal via E_USER_WARNING.
				case self::TYPE_WARNING:
					trigger_error($error_options["message"], E_USER_WARNING);
					break;
			}
		} // @codeCoverageIgnoreEnd

		/** 404 ERROR */
		// Força um erro de objeto não encontrado.
		/** @codeCoverageIgnore */
		static public function force_404() {
			$location_error = dirname($_SERVER["SCRIPT_NAME"]) . "/404";
			header("Location: {$location_error}");
		}
	}
