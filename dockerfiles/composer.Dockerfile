FROM composer:latest

WORKDIR /var/www/proxy

ENTRYPOINT ["composer", "--ignore-platform-reqs"]