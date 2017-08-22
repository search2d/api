FROM composer AS vendor-env
COPY composer.* /app/
RUN composer install --no-dev --no-suggest

FROM search2d/php
COPY . /var/www
COPY --from=vendor-env /app/vendor /var/www/vendor
COPY docker/php/php.ini /usr/local/etc/php/php.ini