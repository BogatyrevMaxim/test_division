setup:
	docker-compose up -d
	docker-compose exec php-fpm composer install

up:
	docker-compose up -d

down:
	docker-compose down

test:
	docker-compose exec php-fpm php ./vendor/bin/phpunit

bash:
	docker-compose exec php-fpm bash