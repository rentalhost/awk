@ECHO off
CLS

SET "filter=%1"
IF [%filter%] == [] (
    SET "filter=."
)

"../vendor/bin/phpunit" ^
    --configuration "phpunit-settings.xml" ^
    --coverage-html "coverage" ^
    --filter %filter% ^
    --colors ^
    tests
