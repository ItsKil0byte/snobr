# https://serversideup.net/open-source/docker-php/docs/guide/understanding-file-permissions

# БАЗОВЫЙ ОБРАЗ

FROM serversideup/php:8.3-fpm-nginx as base

# ОБРАЗ ДЛЯ РАЗРАБОТКИ

FROM base as development

USER root

ARG USER_ID
ARG GROUP_ID

RUN docker-php-serversideup-set-id www-data $USER_ID:$GROUP_ID && \
    docker-php-serversideup-set-file-permissions --owner $USER_ID:$GROUP_ID

USER www-data

# ОБРАЗ ДЛЯ ПРОДА

FROM base as production

COPY --chown=www-data:www-data . /var/www/html