<?php

	// Se estiver em um ambiente de desenvolvimento e o módulo "awk_suite" existir, \
	// adiciona a seguinte rota.
	if($awk->is_development()
	&& Awk_Module::exists("awk_suite")) {
		$router->add_route("suite", "awk_suite->index");
	}
