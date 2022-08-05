<?php

declare(strict_types=1);

namespace MessageNotify;

class Notify
{
    public static function make(): Client
    {
        if (class_exists(\Hyperf\Utils\ApplicationContext::class)) {
            return make(Client::class);
        }

        return new Client();
    }
}
