<?php

    /**
     * Responsável por controlar os handlers de um controlador de eventos.
     */
    class Awk_Event_Handlers {
        /**
         * Armazena os callables registrado no handler.
         * @var callable[]
         */
        public $callables = [];

        /**
         * Adiciona um novo callble.
         * @param callable $event_callable Callable que será adicionado.
         */
        public function add($event_callable) {
            $this->callables[] = $event_callable;
        }

        /**
         * Remove todos os callables registrados.
         * @param  callable|null $event_callable Callable que será considerado na remoção.
         */
        public function remove($event_callable = null) {
            // Se um callable não foi informado, todos serão removidos.
            if($event_callable === null) {
                $this->callables = [];
                return;
            }

            // Caso contrário, remove todos os callables informados.
            foreach($this->callables as $key => $callable) {
                if($callable === $event_callable) {
                    unset($this->callables[$key]);
                }
            }
        }

        /**
         * Executa os callables registrados em ordem, enviando o objeto do evento.
         * Os próximos eventos deverão ser cancelados caso o atual retorne false.
         * @param  Awk_Event_Object $event_object Objeto do evento.
         * @return Awk_Event_Object
         */
        public function trigger($event_object) {
            foreach($this->callables as $callable) {
                // Executa o callable atual e obtém seu resultado.
                $event_args = array_merge([ $event_object ], $event_object->data->get_array());
                $event_object->result = call_user_func_array($callable, $event_args);
                if($event_object->result === false) {
                    break;
                }
            }
        }
    }
