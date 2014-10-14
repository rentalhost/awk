<?php

    /**
     * @covers Awk_Public
     * @covers Awk_Public_Feature
     */
    class Awk_PublicTest extends PHPUnit_Framework_TestCase {
        /**
         * Módulo atual.
         * @var Awk_Module
         */
        static private $module;

        /**
         * Configurações antes da classe.
         */
        static public function setUpBeforeClass() {
            self::$module = Awk_Module::get("awk_suite");
        }

        /**
         * Testa um arquivo público.
         */
        public function testPublicFileLoad() {
            $public_instance = self::$module->public("test1_hello.php");

            $this->assertTrue($public_instance->path->exists());

            return $public_instance;
        }

        /**
         * Verifica o retorno do método get_url().
         * @depends testPublicFileLoad
         * @param  Awk_Public $public_instance Instância de Awk_Public.
         */
        public function testGetUrlMethod($public_instance) {
            $this->assertSame("awk_suite/publics/test1_hello.php", $public_instance->get_url());
        }

        /**
         * Obtém a URL completa, preenchendo SERVER_NAME e SCRIPT_NAME.
         * @depends testPublicFileLoad
         * @param  Awk_Public $public_instance Instância de Awk_Public.
         */
        public function testGetUrlMethodFillingServer($public_instance) {
            // Teste básico.
            $_SERVER["SERVER_NAME"] = "localhost";
            $_SERVER["SCRIPT_NAME"] = "/index.php";
            $_SERVER["SERVER_PORT"] = 80;

            $this->assertSame("http://localhost/awk_suite/publics/test1_hello.php", $public_instance->get_url(true));

            // Testa com um sub-diretório.
            $_SERVER["SCRIPT_NAME"] = "/test/index.php";

            $this->assertSame("http://localhost/test/awk_suite/publics/test1_hello.php", $public_instance->get_url(true));

            // Testa com um SCRIPT_NAME binário.
            $_SERVER["SCRIPT_NAME"] = "/test";

            $this->assertSame("http://localhost/awk_suite/publics/test1_hello.php", $public_instance->get_url(true));

            // Ativa o HTTPS através da porta.
            $_SERVER["SCRIPT_NAME"] = "/index.php";
            $_SERVER["SERVER_PORT"] = getservbyname("https", "tcp");

            $this->assertSame("https://localhost/awk_suite/publics/test1_hello.php", $public_instance->get_url(true));

            // Declara/ativa o HTTPS.
            $_SERVER["SERVER_PORT"] = 80;
            $_SERVER["HTTPS"] = true;

            $this->assertSame("https://localhost/awk_suite/publics/test1_hello.php", $public_instance->get_url(true));

            // Desativa o HTTPS, apesar de declarado.
            $_SERVER["HTTPS"] = "off";

            $this->assertSame("http://localhost/awk_suite/publics/test1_hello.php", $public_instance->get_url(true));
        }
    }
