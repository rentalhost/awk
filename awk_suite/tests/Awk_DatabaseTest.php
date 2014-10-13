<?php

    /**
     * @covers Awk_Database
     * @covers Awk_Database_Feature
     * @requires extension pdo
     */
    class Awk_DatabaseTest extends PHPUnit_Framework_TestCase {
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
         * Obtém a conexão válida.
         * @return  Awk_Database
         */
        static private function getValidConnection() {
            $database_instance = self::$module->database();
            $database_instance->configure(self::$module->settings()->database_configuration);

            return $database_instance;
        }

        /**
         * Inicia a conexão com o banco de dados.
         * Se a conexão falhar, todo o teste será ignorado.
         */
        public function testDatabaseConnect() {
            $this->assertEmpty(null);

            try {
                $database_instance = self::getValidConnection();

                $this->assertTrue($database_instance->connect());

                return $database_instance;
            }
            catch(PDOException $e) {
                $this->markTestSkipped("Failed on connect to database, check settings.");
            }
        }

        /**
         * Testa novamente a conectividade.
         */
        public function testDatabaseConnectReload() {
            $this->testDatabaseConnect();
        }

        /**
         * Executa uma simples query.
         * @depends testDatabaseConnect
         * @param  Awk_Database $database_instance Instância da Awk_Database.
         */
        public function testQuery($database_instance) {
            $this->assertInstanceOf("PDOStatement", $database_instance->query("SELECT TRUE"));
            $this->assertInstanceOf("PDOStatement", $database_instance->query("SELECT TRUE"));
        }

        /**
         * Testa uma conexão inválida.
         */
        public function testDatabaseInvalidConnection() {
            $database_instance = self::$module->database();
            $database_instance->configure([ "password" => uniqid() ]);

            $this->assertFalse($database_instance->connect());
        }

        /**
         * Reinicia a conexão para a conexão válida.
         */
        public function testDatabaseResetToValid() {
            $database_instance = self::getValidConnection();

            $this->assertTrue($database_instance->connect());
        }
    }
