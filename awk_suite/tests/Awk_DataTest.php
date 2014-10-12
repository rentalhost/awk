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
            $this->assertSame([ "test" => "ok" ], self::$module->globals->get_array());
        }

        /**
         * Verifica de forma simplificada se é possível definir e obter valores.
         * @return void
         */
        public function testSimpleSetGet() {
            // Define alguns valores.
            self::$module->globals->set("a", "a");
            self::$module->globals->b = "b";

            // Verifica as informações.
            $this->assertSame("a", self::$module->globals->get("a"));
            $this->assertSame("b", self::$module->globals->b);
        }

        /**
         * Verifica se o método de binding está funcionando corretamente.
         * @return void
         */
        public function testBind() {
            // Define uma variável para binding.
            $value = 1;

            // Globaliza a variável com bind e verifica.
            self::$module->globals->bind("value", $value);
            $this->assertSame(1, self::$module->globals->get("value"));

            // Altera a variável e verifica se o bind foi afetado.
            $value = 2;
            $this->assertSame(2, self::$module->globals->get("value"));
        }

        /**
         * Testa a PropertyAccess.
         * @return void
         */
        public function testPropertyAccess() {
            // Verifica se o isset() corresponde.
            $this->assertTrue(isset(self::$module->globals->value));

            // Remove a variável e verifica novamente.
            unset(self::$module->globals->value);
            $this->assertFalse(isset(self::$module->globals->value));

            // Redefine e atribui novas informações.
            self::$module->globals->set_array([
                "test" => "ok2",
                "other" => "ok3"
            ]);

            $this->assertSame("ok2", self::$module->globals->test);
            $this->assertSame("ok3", self::$module->globals->other);

            // Verifica as informações atuais.
            $this->assertSame([
                "test" => "ok2",
                "a" => "a",
                "b" => "b",
                "other" => "ok3"
            ], self::$module->globals->get_array());

            // Limpa os dados.
            self::$module->globals->clear();
            $this->assertEmpty(self::$module->globals->get_array());
        }
    }
