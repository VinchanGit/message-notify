<?php

declare(strict_types=1);

namespace MessageNotify\Channel;

use Hyperf\Contract\ConfigInterface;
use MessageNotify\Exceptions\MessageNotificationException;
use MessageNotify\Template\AbstractTemplate;

abstract class AbstractChannel
{
    public function getConfig()
    {
        if (class_exists(\Hyperf\Utils\ApplicationContext::class)) {
            $configContext = make(ConfigInterface::class);

            return $configContext->get('message.channels.' . get_class($this));
        }

        throw new MessageNotificationException('ApplicationContext is not exist');
    }

    abstract public function send(AbstractTemplate $template);
}
