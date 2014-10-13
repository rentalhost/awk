<?php

    class AwkSuite_Test_Valid_AutoInit_Library extends Awk_Base {
        static private $init_counter = 0;
        public $init_number = 0;

        public function __construct() {
            $this->init_number = ++self::$init_counter;
        }

        public function get_module_name() {
            return $this->get_module()->get_name();
        }
    }

    $library->register("AwkSuite_Test_Valid_AutoInit_Library", true);
