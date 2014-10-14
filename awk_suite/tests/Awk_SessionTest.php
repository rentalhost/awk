<?php

    /**
     * @covers Awk_Session_Feature
     */
    class Awk_SessionTest extends PHPUnit_Framework_TestCase {
        /**
         * Impede que a sessão seja removida entre os testes.
         * @var array
         */
        protected $backupGlobalsBlacklist = [ "_SESSION" ];

        /**
         * Módulo atual.
         * @var Awk_Module
         */
        static private $module;

        /**
         * Armazena um controlador de sessão.
         * @var Awk_Session_Feature
         */
        static private $module_sessions;

        /**
         * Configurações antes da classe.
         */
        static public function setUpBeforeClass() {
            self::$module = Awk_Module::get("awk_suite");
            self::$module_sessions = self::$module->sessions;
        }

        /**
         * Remove sobras do teste.
         * @depends testSessionKeyIsDir
         */
        static public function setUpAfterClass() {
            unset($_SESSION[self::getSessionKey()]);
        }

        /**
         * Obtém o nome da chave da sessão.
         */
        static private function getSessionKey() {
            return self::$module_sessions->get_session_key();
        }

        /**
         * Testa se a chave da sessão é um diretório.
         */
        public function testSessionKeyIsDir() {
            $session_key = self::getSessionKey();

            $this->assertTrue(is_dir(self::getSessionKey()), "Session key need refer to a directory.\n - Returned: {$session_key}");
        }

        /**
         * Certifica-se que não há dados na sessão.
         * @depends testSessionKeyIsDir
         */
        public function testSessionEmptiness() {
            self::$module_sessions->clear();

            $this->assertSame([], self::$module->session());
        }

        /**
         * Testa a testPropertyAccess.
         * @depends testSessionKeyIsDir
         */
        public function testPropertyAccess() {
            // Define um valor, e verifica.
            self::$module_sessions->test_number = 123;

            $this->assertCount(1, self::$module->session());
            $this->assertSame([ "test_number" => 123 ], self::$module->session());
            $this->assertSame(123, self::$module_sessions->test_number);

            // Define um novo valor, e testa novamente.
            self::$module_sessions->test_string = "hello";

            $this->assertCount(2, self::$module->session());
            $this->assertSame([ "test_number" => 123, "test_string" => "hello" ], self::$module->session());
            $this->assertSame("hello", self::$module_sessions->test_string);

            // Altera um valor, e testa novamente.
            self::$module_sessions->test_number = 456;

            $this->assertSame([ "test_number" => 456, "test_string" => "hello" ], self::$module->session());
            $this->assertSame(456, self::$module_sessions->test_number);

            // Verificação do método.
            self::$module->session("test_array", [ true ]);

            $this->assertSame([
                "test_number" => 456,
                "test_string" => "hello",
                "test_array" => [ true ]
            ], self::$module->session());
            $this->assertSame([ true ], self::$module->session("test_array"));

            // Redefine e define informações.
            self::$module->sessions->set_array([
                "test_string" => "ok2",
                "test_other" => "ok3"
            ]);

            $this->assertSame("ok2", self::$module->session("test_string"));
            $this->assertSame("ok3", self::$module->session("test_other"));

            self::$module->sessions->test_array = [  true ];

            // Verificações de existencia.
            $this->assertTrue(isset(self::$module->sessions->test_array));
            $this->assertFalse(isset(self::$module_sessions->test_unknow));

            // Remove uma definição, e testa.
            unset(self::$module_sessions->test_array);

            $this->assertCount(3, self::$module->session());
            $this->assertSame([
                "test_number" => 456,
                "test_string" => "ok2",
                "test_other" => "ok3"
            ], self::$module->session());
            $this->assertFalse(isset(self::$module_sessions->test_array));

            // Altera via referência.
            $session_reference = &self::$module_sessions->get_array();
            $session_reference["test_string"] = "ok4";
        }

        /**
         * Faz testes com a sessão de outro módulo.
         * @depends testSessionKeyIsDir
         */
        public function testSessionFromOtherModule() {
            // Carrega um outro módulo para comparação.
            // Não afetaremos a sessão original.
            $module_site = Awk_Module::get("site");
            $session_name = uniqid("awk_suite.");

            // Verifica se a sessão já existe.
            $this->assertFalse($module_site->sessions->__isset($session_name));

            // Define um valor para a sessão e testa.
            // O valor não deverá existir no módulo da suite.
            $module_site->sessions->__set($session_name, "hello");

            $this->assertTrue($module_site->sessions->__isset($session_name));
            $this->assertFalse(self::$module_sessions->__isset($session_name));
            $this->assertSame("hello", $module_site->sessions->__get($session_name));

            // Remove a definição, e verifica.
            $module_site->sessions->__unset($session_name);

            $this->assertFalse($module_site->sessions->__isset($session_name));
        }

        /**
         * Certifica-se que não há dados após a execução.
         * @depends testSessionKeyIsDir
         */
        public function testSessionEmptinessAfterExecution() {
            $this->assertSame([
                "test_number" => 456,
                "test_string" => "ok4",
                "test_other" => "ok3"
            ], self::$module->session());

            self::$module_sessions->clear();

            $this->assertSame([], self::$module->session());
        }

        /**
         * Neste ponto, deve haver uma chave de sessão para o módulo atual.
         * @depends testSessionKeyIsDir
         */
        public function testSessionKey() {
            $this->assertTrue(isset($_SESSION[self::getSessionKey()]));
        }
    }

    __halt_compiler();

    // Limpa a sessão e verifica.
    $module->sessions->clear();
    $asserts->expect_equal($module->session(), []);

    // Elimina o bloco de sessão.
    unset($_SESSION[getcwd() . DIRECTORY_SEPARATOR . $module->name]);
