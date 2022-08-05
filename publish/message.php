<?php

declare(strict_types=1);

use MessageNotify\Contracts\MessageNotifyInterface;

return [
    'default' => env('NOTIFY_DEFAULT_CHANNEL', 'mail'),
    'channels' => [
        // 钉钉群机器人
        \MessageNotify\Channel\DingTalkChannel::class => [
            'default' => MessageNotifyInterface::INFO,
            'pipeline' => [
                // 业务信息告警群
                MessageNotifyInterface::INFO => [
                    'token' => env('NOTIFY_DINGTALK_TOKEN', ''),
                    'secret' => env('NOTIFY_DINGTALK_SECRET', ''),
                    'keyword' => env('NOTIFY_DINGTALK_KEYWORD', []),
                ],
                // 错误信息告警群
                MessageNotifyInterface::ERROR => [
                    'token' => env('NOTIFY_DINGTALK_TOKEN', ''),
                    'secret' => env('NOTIFY_DINGTALK_SECRET', ''),
                    'keyword' => env('NOTIFY_DINGTALK_KEYWORD', []),
                ],
            ],
        ],

        // 飞书群机器人
        \MessageNotify\Channel\FeiShuChannel::class => [
            'default' => MessageNotifyInterface::INFO,
            'pipeline' => [
                'info' => [
                    'token' => env('NOTIFY_FEISHU_TOKEN', ''),
                    'secret' => env('NOTIFY_FEISHU_SECRET', ''),
                    'keyword' => env('NOTIFY_FEISHU_KEYWORD'),
                ],
            ],
        ],

        // 邮件
        \MessageNotify\Channel\MailChannel::class => [
            'default' => MessageNotifyInterface::INFO,
            'pipeline' => [
                'info' => [
                    'dsn' => env('NOTIFY_MAIL_DSN'),
                    'from' => env('NOTIFY_MAIL_FROM'),
                    'to' => env('NOTIFY_MAIL_TO'),
                ],
            ],
        ],

        // 企业微信群机器人
        \MessageNotify\Channel\WechatChannel::class => [
            'default' => MessageNotifyInterface::INFO,
            'pipeline' => [
                'info' => [
                    'token' => env('NOTIFY_WECHAT_TOKEN'),
                ],
            ],
        ],
    ],
];
