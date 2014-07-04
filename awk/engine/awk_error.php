<?php

	// Responsável pelo controle de erros.
	class awk_error {
		/** CONST TYPE */
		// Indica um erro do tipo E_USER_ERROR.
		const TYPE_FATAL = "fatal";
		// Indica um erro do tipo E_USER_WARNING.
		const TYPE_WARNING = "warning";

		// Cria um erro com as especificações fornecidas.
		static public function create($error_options) {
			// Definições padrões de um erro.
			$error_options = array_replace([
				// Tipo do erro a ser lançado.
				// @type const TYPE;
				"type" => self::TYPE_WARNING,

				// Mensagem do erro.
				// @type string?;
				"message" => null,
			], $error_options);

			// Determina o tipo da execução do erro.
			switch($error_options["type"]) {
				// Lança um erro fatal via E_USER_ERROR.
				case self::TYPE_FATAL:
					trigger_error($error_options["message"], E_USER_ERROR);
					break;

				// Lança um erro não-fatal via E_USER_WARNING.
				case self::TYPE_WARNING:
					trigger_error($error_options["message"], E_USER_WARNING);
					break;
			}
		}
	}
