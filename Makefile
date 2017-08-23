.PHONY: test
test:
	docker-compose run --rm php php vendor/bin/phpunit

.PHONY: image
image: image-php image-web

.PHONY: image-php
image-php:
	docker build --pull -f Dockerfile-php -t search2d/api-php .

.PHONY: image-web
image-web:
	docker build --pull -f Dockerfile-web -t search2d/api-web .