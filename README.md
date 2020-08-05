# 基于YII创建k8s微服务 | create micro service based on yii and k8s

## 步骤 | steps

### YII微服务 | yii 
- 定义依赖 [`composer.json`](./composer.json) | define php packages
- 安装依赖 `composer install` | install php packages
- 应用入口 [`web/index.php`](./web/index.php) | web entry
- 配置文件 [`config.php`](./config.php) | yii config
- 自动创建表配置 `vendor/bin/yii migrate/create --appconfig=config.php create_post_table --fields="title:string,body:text"` | create migrate
- 自动创建表 `vendor/bin/yii migrate/up --appconfig=config.php` (需注释掉 `config.php` 中 `request` 部分) | apply migrate (create table)
- Model [`models/Post.php`](./models/Post.php)
- Controller [`controllers/PostController.php`](./controllers/PostController.php)

现在你可以试试调用一下API | try request

```
curl -H "Content-Type: application/json" http://localhost:8080/
```

到目前为止的代码 https://github.com/postor/yii2-k8s-example/releases/tag/v0.1.0

### 针对k8s进行配置 | k8s

- [`Dockerfile`](./Dockerfile)
- 数据库从环境变量配置 [`config.php`](./config.php)
- [docker-compose.yml]


现在运行 docker-compose 就可以启动8081端口的服务了 | compose up

```
docker-compose up -d --build
```

到目前为止的代码 https://github.com/postor/yii2-k8s-example/releases/tag/v0.2.0

使用 k8s 之前先要推送镜像，这里我就推到 dockerhub 了 | push to docker hub

```
docker push postor/yii2-k8s-example
```

接下来取巧一下，使用 kompose 转换生成 k8s 的配置 | generate k8s config

```
kompose convert -f docker-compose.yml
```

可以看到生成了两个文件，先创建部署，在创建服务 | create k8s resources

```
kubectl create -f .\post-deployment.yaml
kubectl create -f .\post-service.yaml
```

可以用这些命令来确认一下进行的状态 | check k8s status

```
kubectl get pods
kubectl get deployment
kubectl get service
```

等过些时间，因为要拉取 image 比较耗时，再看 pods 就可以看到运行的服务了 | wait image load and ready 

```
$ kubectl get pods

NAME                    READY   STATUS    RESTARTS   AGE
post-6976cc7889-8snv2   1/1     Running   0          3h12m
```

验证能够从 service 获取数据 | send request to service

```
$ kubectl run curl --image=curlimages/curl -i --tty --rm sh
$ curl http://10.108.221.255:8081

[]
```

### 扩容 | scaling

修改 post-deployment.yaml， 找到 replicas: 1 修改为 | change

```
replicas: 2
```

然后应用更改 | apply

```
kubectl apply -f .\post-deployment.yaml
```

再确认 pods | check status

```
$ kubectl get pods

NAME                    READY   STATUS             RESTARTS   AGE
post-6976cc7889-8snv2   1/1     Running            0          3h31m
post-6976cc7889-pkwrw   1/1     Running            0          3m15s
```

### 更多 | more

请自己解决 | practice yourself

- k8s 外部访问 | request from outside k8s
- 自动扩容 kubernetes hpa | HPA
- 多服务编排 | multi-services