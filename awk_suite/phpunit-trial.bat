@echo off
cls

"../vendor/bin/phpunit" ^
	--strict ^
	--process-isolation ^
	--no-globals-backup ^
	--verbose ^
	--debug ^
	tests
