FROM yiisoftware/yii2-php:7.2-apache

WORKDIR /app

COPY composer.* ./

RUN composer install --no-interaction

COPY . .