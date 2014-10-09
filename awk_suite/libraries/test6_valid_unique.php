<?php

    class AwkSuite_Test_Valid_Unique_Library extends Awk_Base {
        static public function library_unique() {
            return new self;
        }
    }

    $library->register("AwkSuite_Test_Valid_Unique_Library");
