setup:
	docker-compose up -d
	docker-compose exec php-fpm composer install

up:
	docker-compose up -d

test:
	docker-compose exec php-fpm php ./vendor/bin/phpunit

bash:
	docker-compose exec php-fpm bash