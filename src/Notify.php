<?php

declare(strict_types=1);

namespace MessageNotify;

use Hyperf\Utils\ApplicationContext;

class Notify
{
    public static function make(): Client
    {
        if (class_exists(ApplicationContext::class)) {
            return make(Client::class);
        }

        return new Client();
    }
}
