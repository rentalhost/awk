<?php

    /**
     * @covers Awk_Settings
     * @covers Awk_Settings_Feature
     */
    class Awk_SettingsTest extends PHPUnit_Framework_TestCase {
        /**
         * Módulo atual.
         * @var Awk_Module
         */
        static private $module;

        /**
         * Armazena as configurações do módulo.
         * @var Awk_Settings
         */
        static private $module_settings;

        /**
         * Configurações antes da classe.
         */
        static public function setUpBeforeClass() {
            self::$module = Awk_Module::get("awk_suite");
            self::$module_settings = self::$module->settings();
        }

        /**
         * Faz testes no set e get das configurações.
         * @return [type] [description]
         */
        public function testSettingsGetSet() {
            // Verifica se uma configuração está correta.
            $this->assertSame(123, self::$module_settings->test_value);

            // Verifica uma configuração sobrescrita.
            $this->assertSame("after", self::$module_settings->test_overwrited);

            // Altera uma configuração e a testa novamente.
            self::$module_settings->test_created = "abc";
            $this->assertSame("abc", self::$module_settings->test_created);

            // Redefine e define um grupo de configurações.
            self::$module_settings->set_array([
                "test_created" => "def",
                "test_new" => "xyz"
            ]);

            $this->assertSame("def", self::$module_settings->test_created);
            $this->assertSame("xyz", self::$module_settings->test_new);

            // Remove todas as configurações.
            $module_settings_clone = clone self::$module_settings;
            $module_settings_clone->clear();
            $this->assertEmpty($module_settings_clone->get_array());
        }

        /**
         * Testa os métodos mágicos.
         * @return void
         */
        public function testMagicMethods() {
            // Define uma informação, apaga, e verifica sua existência.
            self::$module_settings->test_created = "abc";
            $this->assertTrue(isset(self::$module_settings->test_created));
            unset(self::$module_settings->test_created);
            $this->assertFalse(isset(self::$module_settings->test_created));
        }

        /**
         * Testa os métodos de overwrite.
         * @return void
         */
        public function testOverwritedMethods() {
            $overwrited_path = self::$module_settings->get_overwrited_path();

            $this->assertSame("settings.awk_suite.php", $overwrited_path->get_basename());
            $this->assertTrue($overwrited_path->exists());
        }
    }
