FROM yiisoftware/yii2-php:7.2-apache

WORKDIR /app

COPY composer.* ./

RUN composer install --no-interaction

COPY . .

CMD bash -c "vendor/bin/yii migrate/up --appconfig=config.php --interactive=0 && apache2-foreground"