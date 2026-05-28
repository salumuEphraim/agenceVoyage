FROM node:20-alpine AS assets

WORKDIR /app

COPY package*.json vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm ci && npm run build

FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .
COPY --from=assets /app/public/build ./public/build
COPY conf/nginx/nginx-site.conf /etc/nginx/sites-enabled/default.conf

ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN chmod +x /var/www/html/scripts/*.sh

CMD ["/start.sh"]
