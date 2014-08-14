<?php

	class AwkSuite_Test_Valid_Unique_Library extends AwkBase {
		static public function library_unique() {
			return new self;
		}
	}

	$library->register("AwkSuite_Test_Valid_Unique_Library");
