<?php

    /**
     * Responsável por definir os validadores e transformadores de tipos padrões.
     */
    class Awk_Type_Helper {
        /**
         * Valida o tipo boolean.
         * @param  mixed $value Valor a ser validado.
         * @return boolean
         */
        static public function boolean_validate($value) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN) !== false;
        }

        /**
         * Transforma o tipo boolean.
         * @param  mixed $value Valor a ser transformado.
         * @return boolean
         */
        static public function boolean_transform($value) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN) !== false;
        }

        /**
         * Valida o tipo empty.
         * @param  mixed $value Valor a ser validado.
         * @return boolean
         */
        static public function empty_validate($value) {
            return empty($value);
        }

        /**
         * Transforma o tipo empty.
         * @param  mixed $value Valor a ser transformado.
         * @return null
         */
        static public function empty_transform($value) {
            return null;
        }

        /**
         * Valida o tipo float.
         * @param  mixed $value Valor a ser validado.
         * @return boolean
         */
        static public function float_validate($value) {
            return filter_var($value, FILTER_VALIDATE_FLOAT) !== false &&
                  !is_bool($value);
        }

        /**
         * Transforma o tipo float.
         * @param  mixed $value Valor a ser transformado.
         * @return float
         */
        static public function float_transform($value) {
            return is_scalar($value)
                       ? (float) $value
                       : 0.0;
        }

        /**
         * Valida o tipo int.
         * @param  mixed $value Valor a ser validado.
         * @return boolean
         */
        static public function int_validate($value) {
            return filter_var($value, FILTER_VALIDATE_INT) !== false &&
                  !is_bool($value);
        }

        /**
         * Transforma o tipo int.
         * @param  mixed $value Valor a ser transformado.
         * @return int
         */
        static public function int_transform($value) {
            return is_scalar($value)
                       ? (int) $value
                       : 0;
        }

        /**
         * Valida o tipo null.
         * @param  mixed $value Valor a ser validado.
         * @return boolean
         */
        static public function null_validate($value) {
            return is_null($value);
        }

        /**
         * Transforma o tipo null.
         * @param  mixed $value Valor a ser transformado.
         * @return null
         */
        static public function null_transform($value) {
            return null;
        }

        /**
         * Valida o tipo string.
         * @param  mixed $value Valor a ser validado.
         * @return boolean
         */
        static public function string_validate($value) {
            return is_scalar($value);
        }

        /**
         * Transforma o tipo string.
         * @param  mixed $value Valor a ser transformado.
         * @return string
         */
        static public function string_transform($value) {
            return is_scalar($value)
                       ? (string) $value
                       : "";
        }
    }
