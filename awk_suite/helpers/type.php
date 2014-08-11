<?php

	// Retorna o tipo de uma informação de forma padronizada.
	$helper->add("normalize", function($value) {
		switch(gettype($value)) {
			case "string":
				if(empty($value)) {
					return "string(empty)";
				}

				return "string(\"{$value}\")";
				break;
			case "array":
				if(empty($value)) {
					return "array(empty)";
				}

				return "array(" . json_encode($value, null, 1) . ")";
				break;
			case "integer":
				return "int({$value})";
				break;
			case "double":
				if(is_nan($value)) {
					return "float(nan)";
				}

				return "float({$value})";
				break;
			case "boolean":
				$value = $value ? "true" : "false";
				return "bool({$value})";
				break;
			case "object":
				$value = get_class($value);
				return "object({$value})";
				break;
			case "resource":
				$value = get_resource_type($value);
				return "resource({$value})";
				break;
			case "NULL":
				return "null";
				break;
		}
	});
