<?php

	/**
	 * Responsável por uma query definida em um model.
	 */
	class Awk_Model_Query extends Awk_Module_Base {
		/**
		 * Armazena o nome da query.
		 * @var string
		 */
		private $query_name;

		/**
		 * Armazena o tipo da query.
		 * @var string
		 */
		private $query_type;

		/**
		 * Armazena o definidor da query.
		 * @var string
		 */
		private $query_definer;

		/**
		 * Constrói uma nova query.
		 * @param Awk_Model $parent        Model que construiu a query.
		 * @param string    $query_name    Nome de referência da query.
		 * @param string    $query_type    Tipo específico da query.
		 * @param string    $query_definer Definição da query.
		 */
		public function __construct($parent, $query_name, $query_type, $query_definer) {
			parent::__construct($parent->get_module(), $parent);

			// Define algumas propriedades.
			$this->query_name = $query_name;
			$this->query_type = $query_type;
			$this->query_definer = $query_definer;

			// Se não for um tipo de query suportado, lança uma exceção.
			if(!in_array($query_type, [ "one" ])) {
				Awk_Error::create([
					"message" => "Atualmente, não há suporte para a query do tipo \"{$query_type}\" em um model."
				]);
			} // @codeCoverageIgnore
		} // @codeCoverageIgnore

		/**
		 * Executa a query com os argumentos passados.
		 * @param  mixed[] $query_args Argumentos que serão passados para a query.
		 * @return Awk_Model_Row
		 */
		public function execute($query_args) {
			// Verifica o retorno, baseado no tipo de query.
			switch($this->query_type) {
				// Query do tipo "one" sempre retornará um único resultado.
				// Caso mais de um seja localizado, eles serão descartados.
				case "one":
					$query_result = $this->query($this->query_definer, $query_args);
					if($query_result) {
						$row_instance = new Awk_Model_Row($this->get_module(), $this);
						$row_instance->set_result($query_result);

						return $row_instance;
					}
					break;
			}

			// Se atingir este ponto, signifca que houve um erro na query.
			Awk_Error::create([
				"message" => "Falha ao executar a query."
			]);
		} // @codeCoverageIgnore

		/**
		 * Executa uma query especializada.
		 * @param  string  $query_definer Definição da query.
		 * @param  mixed[] $query_args    Argumentos que serão passados a query.
		 * @return PDO_Statement
		 */
		private function query($query_definer, $query_args) {
			return $this->get_module()->database()->query($query_definer);
		}
	}
