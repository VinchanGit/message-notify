
## 消息通知组件

## 配置文件

发布配置文件`config/message.php`

```bash
hyperf vendor:publish vinchan/message-notify
```

## 安装

```bash
composer require vinchan/message-notify -vvv
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