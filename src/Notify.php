<?php

declare(strict_types=1);

namespace MessageNotify;

use Hyperf\Context\ApplicationContext;

use function Hyperf\Support\make;

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
