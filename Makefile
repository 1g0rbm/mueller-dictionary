init: docker-down docker-up composer-install

docker-up:
	docker-compose --env-file .env.local up -d

docker-down:
	docker-compose down --remove-orphans

docker-pull:
	docker-compose --env-file .env.local pull

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

composer-install:
	docker-compose run --rm php-cli composer install

php-cli:
	docker-compose run --rm php-cli $(ARG)
