.PHONY: test
test:
	docker-compose run --rm php php vendor/bin/phpunit

.PHONY: image
image: image-php image-web

.PHONY: image-php
image-php:
	docker build --squash --pull -f docker/php/Dockerfile -t search2d/api-php .

.PHONY: image-web
image-web:
	docker build --squash --pull -f docker/web/Dockerfile -t search2d/api-web .
