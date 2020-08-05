# 基于YII创建k8s微服务

## 步骤

### YII微服务
- 定义依赖 [`composer.json`](./composer.json)
- 安装依赖 `composer install`
- 应用入口 [`web/index.php`](./web/index.php)
- 配置文件 [`config.php`](./config.php)
- 自动创建表配置 `vendor/bin/yii migrate/create --appconfig=config.php create_post_table --fields="title:string,body:text"`
- 自动创建表 `vendor/bin/yii migrate/up --appconfig=config.php` (需注释掉 `config.php` 中 `request` 部分)
- Model [`models/Post.php`](./models/Post.php)
- Controller [`controllers/PostController.php`](./controllers/PostController.php)

现在你可以试试调用一下API

```
curl -H "Content-Type: application/json" http://localhost:8080/
```

到目前为止的代码 https://github.com/postor/yii2-k8s-example/releases/tag/v0.1.0

### 针对k8s进行配置

- [`Dockerfile`](./Dockerfile)
- 数据库从环境变量配置 [`config.php`](./config.php)
- [docker-compose.yml]


现在运行 docker-compose 就可以启动8081端口的服务了

```
docker-compose up -d --build
```

到目前为止的代码 https://github.com/postor/yii2-k8s-example/releases/tag/v0.2.0

使用 k8s 之前先要推送镜像，这里我就推到 dockerhub 了

```
docker push postor/yii2-k8s-example
```

接下来取巧一下，使用 kompose 转换生成 k8s 的配置

```
kompose convert -f docker-compose.yml
```

可以看到生成了两个文件，先创建部署，在创建服务

```
kubectl create -f .\post-deployment.yaml
kubectl create -f .\post-service.yaml
```

可以用这些命令来确认一下进行的状态

```
kubectl get pods
kubectl get deployment
kubectl get service
```

等过些时间，因为要拉取 image 比较耗时，再看 pods 就可以看到运行的服务了

```
$ kubectl get pods

NAME                    READY   STATUS    RESTARTS   AGE
post-6976cc7889-8snv2   1/1     Running   0          3h12m
```

验证能够从 service 获取数据

```
$ kubectl run curl --image=curlimages/curl -i --tty --rm sh
$ curl http://10.108.221.255:8081

[]
```

### 扩容

修改 post-deployment.yaml， 找到 replicas: 1 修改为

```
replicas: 2
```

然后应用更改

```
kubectl apply -f .\post-deployment.yaml
```

再确认 pods

```
$ kubectl get pods

NAME                    READY   STATUS             RESTARTS   AGE
post-6976cc7889-8snv2   1/1     Running            0          3h31m
post-6976cc7889-pkwrw   1/1     Running            0          3m15s
```

### 更多

请自己解决
- 自动扩容 kubernetes hpa
- 多服务编排