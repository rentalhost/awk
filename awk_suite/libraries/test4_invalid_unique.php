<?php

    class AwkSuite_Invalid2_Test {
        static public function library_unique() {
            return new stdclass;
        }
    }

    $library->register("AwkSuite_Invalid2_Test");
