<?php

    /**
     * Responsável pelo controle de eventos.
     */
    class Awk_Event {
        /**
         * Armazena o target do evento.
         * @var object
         */
        private $target;

        /**
         * Armazena os eventos anexados na instância.
         * @var Awk_Event_Handlers[]
         */
        private $handlers = [];

        /**
         * Constrói.
         * @param object $target Objeto responsável pela criação do controle de eventos.
         */
        public function __construct($target) {
            $this->target = $target;
        }

        /**
         * Converte uma lista de eventos em um array.
         * @param  string $events_name Nome dos eventos.
         * @return string[]|null
         */
        static private function get_events_array($events_name) {
            if(preg_match_all("/\S+/", $events_name, $events_match)) {
                return $events_match[0];
            }
        }

        /**
         * Obtém os handlers de eventos.
         * @param  string $event_name Nome do evento.
         * @return Awk_Event_Handlers
         */
        private function get_event_handlers($event_name) {
            // Se já houve a definição, a retorna.
            if(array_key_exists($event_name, $this->handlers)) {
                return $this->handlers[$event_name];
            }

            // Caso contrário, cria uma nova instância e retorna.
            return $this->handlers[$event_name] = new Awk_Event_Handlers;
        }

        /**
         * Anexa um novo evento.
         * @param  string   $events_name    Nome dos eventos, separados por espaço.
         * @param  callable $event_callable Callable que será executado ao ativar o evento.
         */
        public function on($events_name, $event_callable) {
            $events_name = self::get_events_array($events_name);
            if($events_name) {
                foreach($events_name as $event_name) {
                    $event_handlers = $this->get_event_handlers($event_name);
                    $event_handlers->add($event_callable);
                }
            }
        }

        /**
         * Remove um evento.
         * @param  string   $events_name    Nome dos eventos que serão removidos.
         * @param  callable $event_callable Callable que será considerado na remoção.
         */
        public function off($events_name, $event_callable = null) {
            $events_name = self::get_events_array($events_name);
            if($events_name) {
                foreach($events_name as $event_name) {
                    $event_handlers = $this->get_event_handlers($event_name);
                    $event_handlers->remove($event_callable);
                }
            }
        }

        /**
         * Verifica se um evento possui callables ou se foi definido.
         * @param  string  $event_name Nome do evento que será testado.
         * @return boolean
         */
        public function has($event_name) {
            return array_key_exists($event_name, $this->handlers)
                && count($this->handlers[$event_name]->callables);
        }

        /**
         * Ativa e executa um evento.
         * @param  string  $event_name Nome do evento que será executado.
         * @param  mixed[] $event_data Dados que serão passados a execução.
         * @return Awk_Event_Object
         */
        public function trigger($event_name, $event_data = null) {
            // Prepara a resposta que serão enviadas aos handlers.
            $event_object = new Awk_Event_Object;
            $event_object->data = new Awk_Data($event_data);
            $event_object->target = $this->target;
            $event_object->type = $event_name;

            // Envia a resposta para o controle de handlers.
            $event_handlers = $this->get_event_handlers($event_name);
            $event_handlers->trigger($event_object);

            // Retorna o objeto gerado.
            return $event_object;
        }
    }
