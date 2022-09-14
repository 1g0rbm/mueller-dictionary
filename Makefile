init: docker-down docker-up composer-install

docker-up:
	docker-compose --env-file .env.local up --build -d

docker-down:
	docker-compose down --remove-orphans

docker-pull:
	docker-compose --env-file .env.local pull

docker-build:
	docker-compose --env-file .env.local build

tests: tests-unit tests-functional

tests-unit:
	docker-compose run --rm php-cli bin/phpunit --group unit $(ARG)

tests-functional: create-test-db run-test-migrations load-test-fixtures run-tests-functional remove-test-db

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

create-test-db:
	docker-compose run --rm php-cli bin/console --env=test --no-interaction doctrine:database:create

remove-test-db:
	docker-compose run --rm php-cli bin/console --env=test --force doctrine:database:drop

run-test-migrations:
	docker-compose run --rm php-cli bin/console --env=test --no-interaction doctrine:migrations:migrate

run-tests-functional:
	docker-compose run --rm php-cli bin/phpunit --group functional $(ARG)

load-test-fixtures:
	docker-compose run --rm php-cli bin/console --env=test --no-interaction hautelook:fixtures:load
