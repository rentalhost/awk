<?php

	class AwkSuite_Test_Valid_AutoInit_Library extends AwkBase {
		static private $init_counter = 0;
		public $init_number = 0;

		public function __construct() {
			$this->init_number = ++self::$init_counter;
		}
	}

	$library->register("AwkSuite_Test_Valid_AutoInit_Library", true);
