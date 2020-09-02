lint:
	./vendor/bin/phplint
	./vendor/bin/php-cs-fixer fix --diff --dry-run .

fix:
	./vendor/bin/php-cs-fixer fix --diff .

test:
	./vendor/phpunit/phpunit/phpunit tests
