<?php

	// Responsável por testes nos tipos padrão do awk.
	// type_list => [ value, validate?, transform ];

	$helper = $module->helper("type");

	// Valida um lista de tipos.
	$type_list_callback = function($type, $type_list) use($awk, $helper, $asserts) {
		$type_handler = $awk->type($type);
		foreach($type_list as $type_item) {
			$type_description = "{$type}: " . $helper->call("normalize", $type_item[0]);

			$asserts->expect_equal($type_handler->validate($type_item[0]), $type_item[1], "validate({$type_description})");
			$asserts->expect_equal($type_handler->transform($type_item[0]), $type_item[2], "transform({$type_description})");
		}
	};

	/** BOOLEAN */
	$type_list_callback("boolean", [
		[ true,		true,	true ],
		[ false,	false,	false ],
		[ "on",		true,	true ],
		[ "yes",	true,	true ],
		[ "1",		true,	true ],
		[ "0",		false,	false ],
		[ "",		false,	false ],
		[ "-1",		false,	false ],
		[ " ",		false,	false ],
		[ 1,		true,	true ],
		[ 1.5,		false,	false ],
		[ 0,		false,	false ],
		[ -1,		false,	false ],
		[ null,		false,	false ],
		[ [],		false,	false ],
		[ [true],	false,	false ],
	]);

	/** NULL */
	$type_list_callback("null", [
		[ true,		false,	null ],
		[ false,	false,	null ],
		[ "on",		false,	null ],
		[ "yes",	false,	null ],
		[ "1",		false,	null ],
		[ "0",		false,	null ],
		[ "",		false,	null ],
		[ "-1",		false,	null ],
		[ " ",		false,	null ],
		[ 1,		false,	null ],
		[ 1.5,		false,	null ],
		[ 0,		false,	null ],
		[ -1,		false,	null ],
		[ null,		true,	null ],
		[ [],		false,	null ],
		[ [true],	false,	null ],
	]);

	/** EMPTY */
	$type_list_callback("empty", [
		[ true,		false,	null ],
		[ false,	true,	null ],
		[ "on",		false,	null ],
		[ "yes",	false,	null ],
		[ "1",		false,	null ],
		[ "0",		true,	null ],
		[ "",		true,	null ],
		[ "-1",		false,	null ],
		[ " ",		false,	null ],
		[ 1,		false,	null ],
		[ 1.5,		false,	null ],
		[ 0,		true,	null ],
		[ -1,		false,	null ],
		[ null,		true,	null ],
		[ [],		true,	null ],
		[ [true],	false,	null ],
	]);

	/** INT */
	$type_list_callback("int", [
		[ true,		false,	1 ],
		[ false,	false,	0 ],
		[ "on",		false,	0 ],
		[ "yes",	false,	0 ],
		[ "1",		true,	1 ],
		[ "0",		true,	0 ],
		[ "",		false,	0 ],
		[ "-1",		true,	-1 ],
		[ " ",		false,	0 ],
		[ 1,		true,	1 ],
		[ 1.5,		false,	1 ],
		[ 0,		true,	0 ],
		[ -1,		true,	-1 ],
		[ null,		false,	0 ],
		[ [],		false,	0 ],
		[ [true],	false,	0 ],
	]);

	/** FLOAT */
	$type_list_callback("float", [
		[ true,		false,	1.0 ],
		[ false,	false,	0.0 ],
		[ "on",		false,	0.0 ],
		[ "yes",	false,	0.0 ],
		[ "1",		true,	1.0 ],
		[ "0",		true,	0.0 ],
		[ "",		false,	0.0 ],
		[ "-1",		true,	-1.0 ],
		[ " ",		false,	0.0 ],
		[ 1,		true,	1.0 ],
		[ 1.5,		true,	1.5 ],
		[ 0,		true,	0.0 ],
		[ -1,		true,	-1.0 ],
		[ null,		false,	0.0 ],
		[ [],		false,	0.0 ],
		[ [true],	false,	0.0 ],
	]);

	/** STRING */
	$type_list_callback("string", [
		[ true,		true,	"1" ],
		[ false,	true,	"" ],
		[ "on",		true,	"on" ],
		[ "yes",	true,	"yes" ],
		[ "1",		true,	"1" ],
		[ "0",		true,	"0" ],
		[ "",		true,	"" ],
		[ "-1",		true,	"-1" ],
		[ " ",		true,	" " ],
		[ 1,		true,	"1" ],
		[ 1.5,		true,	"1.5" ],
		[ 0,		true,	"0" ],
		[ -1,		true,	"-1" ],
		[ null,		false,	"" ],
		[ [],		false,	"" ],
		[ [true],	false,	"" ],
	]);
