
## 消息通知组件

## 功能

* 监控发送应用异常
* 支持多种通道(钉钉群机器人、飞书群机器人、邮件、QQ 频道机器人、企业微信群机器人)
* 支持扩展自定义通道

## 环境要求

* hyperf >= 2.0

## 安装

```bash
composer require vinchan/message-notify -vvv
```

## 配置文件

发布配置文件`config/message.php`

```bash
hyperf vendor:publish vinchan/message-notify
```


## 使用
```php
Notify::make()->setChannel(Channel::class)
->setTemplate(Template::class)->setParameter(Parameter::class)
->setAt(['all'])
->send();
```


## 模拟使用
```php
Notify::make()->setChannel(Channel::class)
->setTemplate(Template::class)
->setTitle('标题')->setText('内容')->setAt(['all'])->setPipeline('info')
->send();
```