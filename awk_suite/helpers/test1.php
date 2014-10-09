<?php

	/**
	 * Função hello().
	 * @return string
	 */
	$helper->add("hello", function($complete) {
		return "Hello {$complete}!";
	});
