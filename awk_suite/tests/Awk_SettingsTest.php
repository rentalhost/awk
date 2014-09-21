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
			$this->assertSame("settings.awk_suite.php", basename(self::$module_settings->overwrite_path()));
			$this->assertTrue(self::$module_settings->overwrite_exists());
		}
	}
