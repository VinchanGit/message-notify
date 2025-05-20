<?php

declare(strict_types=1);
/**
 * Copyright (c) The Vinchan , Distributed under the software license
 */

namespace MessageNotify\Channel;

use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use MessageNotify\Exceptions\MessageNotificationException;
use MessageNotify\Template\AbstractTemplate;

use function Hyperf\Support\make;

abstract class AbstractChannel
{
    public function getConfig()
    {
        if (class_exists(ApplicationContext::class)) {
            return make(ConfigInterface::class)->get('message.channels.' . get_class($this));
        }

        throw new MessageNotificationException('ApplicationContext is not exist');
    }

    abstract public function send(AbstractTemplate $template);
}
