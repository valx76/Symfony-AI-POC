php-cs-fixer:
	./vendor/bin/php-cs-fixer check src -v

phpstan:
	./vendor/bin/phpstan analyse src tests
