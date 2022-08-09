<?php

declare(strict_types=1);

namespace MessageNotify;

use MessageNotify\Contracts\MessageNotifyInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                MessageNotifyInterface::class => Client::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config of message client.',
                    'source' => __DIR__ . '/../publish/message.php',
                    'destination' => BASE_PATH . '/config/autoload/message.php',
                ],
            ],
        ];
    }
}
