<?php

	// Executa testes no próprio assert.
	$asserts->expect_equal("a", "a");

	// Verifica uma exceção.
	$asserts->expect_exception(function() { throw new Exception("Test"); }, "Exception");
	$asserts->expect_exception(function() { throw new Exception("Test"); }, "Exception", "Test");
