server:
	symfony server:start --no-tls
	
client:
	npm run dev-server

fixtures:
	php bin/console doctrine:fixtures:load --no-interaction

clear-cache:
	php bin/console cache:clear

test:
	php bin/phpunit

migration:
	php bin/console make:migration

migrate:
	php bin/console doctrine:migrations:migrate