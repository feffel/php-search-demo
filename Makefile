build: 
	docker-compose build
up:
	docker-compose up
stop:
	docker-compose stop
test:
	docker-compose exec app ./vendor/bin/phpunit
down:
	docker-compose down
logs:
	docker-compose logs -f
install:
	docker run --rm -v $(shell pwd):/app composer/composer install
genkey:
	docker-compose exec app php artisan key:generate
optimize:
	docker-compose exec app php artisan optimize
shell:
	docker-compose exec app php -a
init:
	make install
	make build
	docker-compose up -d
	cp .env.example .env
	make genkey
	make optimize
