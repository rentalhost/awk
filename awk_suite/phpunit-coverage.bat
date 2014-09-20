@echo off
cls

"../vendor/bin/phpunit" ^
	--strict ^
	--process-isolation ^
	--no-globals-backup ^
	--coverage-html "publics/coverage" ^
	--verbose ^
	--debug ^
	tests
