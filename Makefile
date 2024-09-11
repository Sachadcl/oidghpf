server:
	symfony server:start --no-tls
	
client:
	npm run dev-server

fixtures:
	php bin/console doctrine:fixtures:load --no-interaction

test-fixtures:
	php bin/console doctrine:fixtures:load --no-interaction --env=test
	
clear-cache:
	php bin/console cache:clear

test:
	php bin/phpunit

migration:
	php bin/console make:migration

migrate:
	php bin/console doctrine:migrations:migrate

test-database:
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:create --env=test