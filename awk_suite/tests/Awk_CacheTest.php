<?php

    /**
     * @covers Awk_Cache
     * @covers Awk_Cache_Feature
     */
    class Awk_CacheTest extends PHPUnit_Framework_TestCase {
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
         * Testa um arquivo de cache.
         */
        public function testCache() {
            // Cria uma instância de cache.
            $cache_instance = self::$module->cache("test1");

            // Remove o diretório de cache.
            $this->testCacheDirRemove();

            // O diretório de cache não deve existir.
            $cache_dir = self::$module->path->get() . "/caches";
            $this->assertFalse(is_dir($cache_dir));

            // O arquivo não deve existir no momento.
            $this->assertFalse(self::$module->caches->exists("test1"));
            $this->assertFalse($cache_instance->path->exists());
            $this->assertFalse($cache_instance->get());

            // Verifica seu nome e sua referência.
            $this->assertSame("test1", $cache_instance->name);
            $this->assertSame("5a/105e8b9d40e1329780d62ea2265d8a", $cache_instance->hash);

            // Define um valor para o objeto.
            $cache_instance->set("Hello World!");

            // Agora o arquivo deve existir e conter uma informação.
            $this->assertTrue(is_dir($cache_dir));
            $this->assertTrue($cache_instance->path->exists());
            $this->assertSame("Hello World!", $cache_instance->get());

            // Remove todos os dados gerados.
            $cache_instance->remove();

            // Verifica a existência do cache.
            $this->assertFalse($cache_instance->path->exists());
            $this->assertFalse($cache_instance->get());
        }

        /**
         * Cria um arquivo de cache único.
         */
        public function testCacheUnique() {
            $test1 = self::$module->cache();
            $test2 = self::$module->cache();

            // Dois arquivos nunca podem ser iguais.
            $this->assertTrue($test1->hash !== $test2->hash);
        }

        /**
         * Cria um arquivo de cache com base em uma não-string.
         * @dataProvider providerCacheArrayOrObject
         */
        public function testCacheArrayOrObject($expected_hash, $cache_object) {
            $this->assertSame($expected_hash, self::$module->cache($cache_object)->hash);
        }

        /**
         * Provedor de objetos.
         */
        public function providerCacheArrayOrObject() {
            return [
                [ "da/c2d276b16b3e3e2b4d38b2f7dc7731", [ "name" => "test" ] ],
                [ "f7/827bf44040a444ac855cd67adfb502", new stdClass ],
                [ "fc/dad500ea6bc76788bf3e3e76273315", 12345 ],
                [ "43/1014e4a761ea216e9a35f20aaec61c", true ],
            ];
        }

        /**
         * Remove a pasta de cache.
         */
        public function testCacheDirRemove() {
            // Remove o diretório de cache.
            $cache_dir = self::$module->path->get() . "/caches";
            self::$module->helper("utils")->call("rmdir_recursively", $cache_dir);

            // Verifica se o diretório foi excluído corretamente.
            $this->assertFalse(is_dir($cache_dir));
        }
    }
