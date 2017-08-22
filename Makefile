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

.PHONY: push
push: push-php push-web

.PHONY: push-php
push-php: image-php
	docker tag search2d/api-php:latest 987340244598.dkr.ecr.ap-northeast-1.amazonaws.com/search2d/api-php:latest
	docker push 987340244598.dkr.ecr.ap-northeast-1.amazonaws.com/search2d/api-php:latest

.PHONY: push-web
push-web: image-web
	docker tag search2d/api-web:latest 987340244598.dkr.ecr.ap-northeast-1.amazonaws.com/search2d/api-web:latest
	docker push 987340244598.dkr.ecr.ap-northeast-1.amazonaws.com/search2d/api-web:latest
