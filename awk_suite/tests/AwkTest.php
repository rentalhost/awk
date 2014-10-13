<?php

    /**
     * @covers Awk
     */
    class AwkTest extends PHPUnit_Framework_TestCase {
        /**
         * Armazena o valor original.
         */
        static private $router_default_original;
        static private $router_file_default_original;

        /**
         * Retorna a instância de Settings do módulo.
         */
        static private function getAwkSettings() {
            return Awk_Module::get("awk")->settings();
        }

        /**
         * Prepara uma configuração temporária.
         */
        static public function setupBeforeClass() {
            $awk_settings = self::getAwkSettings();

            self::$router_default_original = $awk_settings->router_default;
            self::$router_file_default_original = $awk_settings->router_file_default;
        }

        /**
         * Restaura as configurações originais.
         */
        static public function setupAfterClass($awk_settings) {
            $awk_settings = self::getAwkSettings();
            $awk_settings->router_default = self::$router_default_original;
            $awk_settings->router_file_default = self::$router_file_default_original;
        }

        /**
         * Testa a inicialização padrão (URL).
         */
        public function testAwkDefaultInit() {
            $awk_settings = self::getAwkSettings();
            $awk_settings->router_default = "router@awk_suite->test1_basic";

            $_SERVER["REQUEST_URI"] = null;

            Awk::init();

            $this->expectOutputString("root");
        }

        /**
         * Testa a inicialização de arquivo.
         */
        public function testAwkFileInit() {
            $awk_settings = self::getAwkSettings();
            $awk_settings->router_file_default = "router@awk_suite->test6_basic.file";

            $_SERVER["REDIRECT_PUBLICS"] = true;
            $_SERVER["SCRIPT_NAME"] = null;
            $_SERVER["REQUEST_URI"] = "/publics/test1_hello.php";

            Awk::init();

            $this->expectOutputString("Hello World!");
        }

        /**
         * Testa um erro de captura inicial.
         * @expectedException Exception
         * @expectedExceptionMessage /test/404
         */
        public function testAwkFail() {
            $awk_settings = self::getAwkSettings();
            $awk_settings->router_default = "router@awk_suite->unexistent";

            $_SERVER["SCRIPT_NAME"] = "/test/index.php";
            $_SERVER["REQUEST_URI"] = null;

            Awk::init();
        }
    }
