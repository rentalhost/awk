<?php

    /**
     * Nome do framework.
     * @var string
     */
    $settings->framework_name = "Awk";

    /**
     * Versão do framework.
     * @var integer[]
     */
    $settings->framework_version = [0, 1, 0];

    /**
     * Se o projeto está em modo de desenvolvimento.
     * @var boolean
     */
    $settings->project_development_mode = $module->is_localhost();

    /**
     * Define o módulo da rota inicial de páginas.
     * @var string
     */
    $settings->router_default = "router@site->index";
