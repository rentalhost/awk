<?php

    /**
     * @covers Awk_Error
     */
    class Awk_ErrorTest extends PHPUnit_Framework_TestCase {
        /**
         * Testa uma exceção.
         * @expectedException Awk_Exception
         * @expectedExceptionMessage Message
         * @expectedExceptionMessage 1000
         * @return void
         */
        public function testException() {
            Awk_Error::create([
                "message" => "Message",
                "code" => 1000
            ]);
        }

        /**
         * Testa um erro fatal.
         * @expectedException Exception
         * @expectedExceptionMessage OK
         * @return void
         */
        public function testFatalError() {
            Awk_Error::create([ "type" => Awk_Error::TYPE_FATAL ]);
        }

        /**
         * Testa um erro de aviso.
         * @expectedException Exception
         * @expectedExceptionMessage OK
         * @return void
         */
        public function testWarningError() {
            Awk_Error::create([ "type" => Awk_Error::TYPE_WARNING ]);
        }

        /**
         * Testa um erro de tipo desconhecido.
         * @expectedException Awk_Error_NotSupportedType_Exception
         * @expectedExceptionMessage Erro do tipo "unknow" não é suportado.
         * @return void
         */
        public function testUnknowTypeError() {
            Awk_Error::create([ "type" => "unknow" ]);
        }

        /**
         * Testa um erro de redirecionamento.
         * @expectedException Exception
         * @expectedExceptionMessage /test/404
         * @return void
         */
        public function testForce404() {
            $_SERVER["SCRIPT_NAME"] = "/test/index.php";

            Awk_Error::force_404();
        }
    }
