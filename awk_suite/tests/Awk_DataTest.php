<?php

    /**
     * @covers Awk_Data
     */
    class Awk_DataTest extends PHPUnit_Framework_TestCase {
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
         * Obtém todos os dados armazenados globalmente do módulo.
         * @return void
         */
        public function testGlobalGetAll() {
            $this->assertSame([ "test" => "ok" ], self::$module->get_globals()->get_array());
        }

        /**
         * Verifica de forma simplificada se é possível definir e obter valores.
         * @return void
         */
        public function testSimpleSetGet() {
            // Define alguns valores.
            self::$module->get_globals()->set("a", "a");
            self::$module->get_globals()->b = "b";

            // Verifica as informações.
            $this->assertSame("a", self::$module->get_globals()->get("a"));
            $this->assertSame("b", self::$module->get_globals()->b);
        }

        /**
         * Verifica se o método de binding está funcionando corretamente.
         * @return void
         */
        public function testBind() {
            // Define uma variável para binding.
            $value = 1;

            // Globaliza a variável com bind e verifica.
            self::$module->get_globals()->bind("value", $value);
            $this->assertSame(1, self::$module->get_globals()->get("value"));

            // Altera a variável e verifica se o bind foi afetado.
            $value = 2;
            $this->assertSame(2, self::$module->get_globals()->get("value"));
        }

        /**
         * Testa a PropertyAccess.
         * @return void
         */
        public function testPropertyAccess() {
            // Verifica se o isset() corresponde.
            $this->assertTrue(isset(self::$module->get_globals()->value));

            // Remove a variável e verifica novamente.
            unset(self::$module->get_globals()->value);
            $this->assertFalse(isset(self::$module->get_globals()->value));

            // Redefine e atribui novas informações.
            self::$module->get_globals()->set_array([
                "test" => "ok2",
                "other" => "ok3"
            ]);

            $this->assertSame("ok2", self::$module->get_globals()->test);
            $this->assertSame("ok3", self::$module->get_globals()->other);

            // Verifica as informações atuais.
            $this->assertSame([
                "test" => "ok2",
                "a" => "a",
                "b" => "b",
                "other" => "ok3"
            ], self::$module->get_globals()->get_array());

            // Limpa os dados.
            self::$module->get_globals()->clear();
            $this->assertEmpty(self::$module->get_globals()->get_array());
        }
    }
