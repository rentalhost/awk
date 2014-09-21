<?php

	/**
	 * Função hello().
	 */
	$helper->add("hello", function($complete) {
		return "Hello {$complete}!";
	});
