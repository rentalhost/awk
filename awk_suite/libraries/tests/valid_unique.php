<?php

	class awk_suite_test_valid_unique_library extends awk_base {
		static public function library_unique() {
			return new self;
		}
	}

	$library->register("awk_suite_test_valid_unique_library");
