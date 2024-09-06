server:
	symfony server:start --no-tls
	
npm:
	npm run watch

fixtures:
	php bin/console doctrine:fixtures:load --no-interaction