## Message Notification Component

## Features

* Monitor and send application exceptions
* Supports multiple channels (DingTalk group bot, Feishu group bot, Email, QQ channel bot, WeChat Work group bot)
* Supports custom channel extensions

## Requirements

| Component Version | Framework Version |
|-------------------|-------------------|
| v2.0              | hyperf 2.0.*      |
| v3.0              | hyperf 3.0.*      |
| v3.0              | hyperf 3.1.*      |

## Installation

```bash
composer require vinchan/message-notify -vvv
```

## Configuration

Publish the configuration file config/message.php

```bash
hyperf vendor:publish vinchan/message-notify
```


## Usage
```php
Notify::make()->setChannel(DingTalkChannel::class)
->setTemplate(Text::class)
->setTitle('Title')->setText('Content')->setAt(['all'])->setPipeline('info')
->send();
```

## Channels

| Channel Name  | Namespace                                   | Supported Formats          |
|-------|----------------------------------------|---------------|
| DingTalk   | \MessageNotify\Channel\DingTalkChannel | Text、Markdown |
| Feishu   | \MessageNotify\Channel\FeiShuChannel   | Text、Markdown |
| WeChat Work | \MessageNotify\Channel\WechatChannel   | Text、Markdown |
| Email    | \MessageNotify\Channel\MailChannel     | Text、Markdown |

## Formats

| Format Name     | Namespace                             |
|----------|----------------------------------|
| Text     | \MessageNotify\Template\Text     |
| Markdown | \MessageNotify\Template\Markdown |

## License

MIT License. See License File for more information.


