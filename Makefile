docker-pull:
	docker-compose pull

docker-build:
	docker-compose --env-file .env.local build

test:
	docker-compose run --rm php-cli bin/phpunit $(ARG)

lint: php-lint php-cs-fix psalm

php-lint:
	docker-compose run --rm php-cli composer lint

php-cs-fix:
	docker-compose run --rm php-cli composer php-cs-fixer

psalm:
	docker-compose run --rm php-cli composer psalm

console:
	docker-compose run --rm php-cli bin/console $(ARG)

composer:
	docker-compose run --rm php-cli composer $(ARG)
