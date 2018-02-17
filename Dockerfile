FROM composer/composer as build-env
WORKDIR /build

COPY composer*.json ./
RUN composer install
COPY . .

FROM nginx:latest
WORKDIR /var/www

COPY ./site.conf /etc/nginx/sites-available/default

COPY --from=build-env /build/ ./
EXPOSE 80