FROM php:7.4-cli-buster

RUN apt-get -y update \
    && DEBIAN_FRONTEND=noninteractive apt-get -y --no-install-recommends install libffi-dev \
    && docker-php-ext-configure ffi --with-ffi \
    && docker-php-ext-install ffi \
    && apt-get clean \
    && rm -rf /tmp/* /var/lib/apt/lists/* /var/cache/apt/archives/*
