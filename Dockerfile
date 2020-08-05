FROM yiisoftware/yii2-php

WORKDIR /app

COPY composer.* .

RUN composer install --no-interaction

COPY . .