docker-pull:
	docker-compose pull

docker-build:
	docker-compose --env-file .env.local build

test:
	docker-compose run --rm php-cli bin/phpunit $(ARG)

console:
	docker-compose run --rm php-cli bin/console $(ARG)

composer:
	docker-compose run --rm php-cli composer $(ARG)
