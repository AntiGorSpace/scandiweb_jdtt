FROM php:8.2.1-alpine

RUN docker-php-ext-install mysqli


COPY --from=composer /usr/bin/composer /usr/bin/composer