server:
	symfony server:start --no-tls
	
npm:
	npm run dev-server

fixtures:
	php bin/console doctrine:fixtures:load --no-interaction

clear-cache:
	php bin/console cache:clear