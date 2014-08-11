<?php

	$asserts->expect_equal(awk_path::normalize("abc/../abc"), "abc");
