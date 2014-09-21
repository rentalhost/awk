<?php

	/**
	 * @covers Awk_Private
	 * @covers Awk_Private_Feature
	 */
	class Awk_PrivateTest extends PHPUnit_Framework_TestCase {
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
		 * Executa testes em um diretório privado com apenas um arquivo.
		 * @return void
		 */
		public function testPrivateOneFileDirectory() {
			$private_instance = self::$module->private("test1_one_file");

			$this->assertCount(1, iterator_to_array($private_instance->get_files(true)));
			$this->assertCount(1, iterator_to_array($private_instance->get_files(false)));
			$this->assertTrue($private_instance->exists());
		}

		/**
		 * Executa testes em um diretório privado com dois arquivos (um arquivo e uma pasta).
		 * @return void
		 */
		public function testPrivateMoreFilesDirectory() {
			$private_instance = self::$module->private("test2_files");

			$this->assertCount(2, iterator_to_array($private_instance->get_files(true)));
			$this->assertCount(1, iterator_to_array($private_instance->get_files(false)));
			$this->assertTrue($private_instance->exists());
		}
	}
