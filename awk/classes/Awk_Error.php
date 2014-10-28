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
         * @throws Awk_Error_NotSupportedType_Exception
         *         Caso um tipo não suportado seja informado.
         */
        static public function create($error_options) {
            // Definições padrões de um erro.
            $error_options = array_replace([
                /**
                 * Tipo de erro a ser lançado.
                 * @var string
                 */
                "type" => self::TYPE_EXCEPTION,

                /**
                 * Tipo de exceção que será lançada.
                 * @var string
                 */
                "exception" => "Awk_Exception",

                /**
                 * Mensagem de erro.
                 * @var string
                 */
                "message" => null,

                /**
                 * Código do erro.
                 * @var integer
                 */
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
            throw new Awk_Error_NotSupportedType_Exception($error_options["type"]);
        }

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
