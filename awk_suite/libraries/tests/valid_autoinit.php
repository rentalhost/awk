<?php

	class awk_suite_test_valid_autoinit_library extends awk_base {
		static private $init_counter = 0;
		public $init_number = 0;

		public function __construct() {
			$this->init_number = ++self::$init_counter;
		}
	}

	$library->register("awk_suite_test_valid_autoinit_library", true);
