# 基于YII创建k8s微服务

## 步骤

### YII微服务
- 定义依赖 [`composer.json`](./composer.json)
- 安装依赖 `composer install`
- 应用入口 [`web/index.php`](./web/index.php)
- 配置文件 [`config.php`](./config.php)
- 自动创建表配置 `vendor/bin/yii migrate/create --appconfig=config.php create_post_table --fields="title:string,body:text"`
- 自动创建表 `vendor/bin/yii migrate/up --appconfig=config.php`
- Model [`models/Post.php`](./models/Post.php)
- Controller [`controllers/PostController.php`](./controllers/PostController.php)