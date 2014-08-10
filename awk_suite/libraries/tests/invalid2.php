<?php

	class awk_suite_invalid2_test {
		static public function library_unique() {
			return new stdclass;
		}
	}

	$library->register("awk_suite_invalid2_test");
