<?php

declare(strict_types=1);

use MessageNotify\Channel\DingTalkChannel;
use MessageNotify\Channel\FeiShuChannel;
use MessageNotify\Channel\MailChannel;
use MessageNotify\Channel\WechatChannel;
use MessageNotify\Contracts\MessageNotifyInterface;

return [
    'default' => env('NOTIFY_DEFAULT_CHANNEL', 'mail'),
    'channels' => [
        // 钉钉群机器人
        DingTalkChannel::class => [
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
        FeiShuChannel::class => [
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
        MailChannel::class => [
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
        WechatChannel::class => [
            'default' => MessageNotifyInterface::INFO,
            'pipeline' => [
                'info' => [
                    'token' => env('NOTIFY_WECHAT_TOKEN'),
                ],
            ],
        ],
    ],
];
