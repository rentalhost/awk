<?php

	$asserts->expect_equal(Awk_Path::normalize("abc/../abc"), "abc");
