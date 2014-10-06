@ECHO off
CLS

SET "filter=%1"
IF [%filter%] == [] (
	SET "filter=."
)

"../vendor/bin/phpunit" ^
	--configuration "phpunit-settings.xml" ^
	--filter %filter% ^
	--colors ^
	tests
