<?php

declare(strict_types=1);
/**
 * Copyright (c) The Vinchan , Distributed under the software license
 */

namespace MessageNotifyTest;

use MessageNotify\Channel\MailChannel;
use MessageNotify\Contracts\MessageNotifyInterface;
use MessageNotify\Exceptions\MessageNotificationException;
use MessageNotify\Template\Markdown;
use MessageNotify\Template\Text;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class MailChannelTest extends TestCase
{
    private MailChannel $mailChannel;

    private array $config;

    /**
     * 设置测试环境.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // 读取.env配置
        $this->loadEnvConfig();

        // 创建测试用MailChannel
        $this->mailChannel = new class($this->config) extends MailChannel {
            private array $testConfig;

            public function __construct(array $config)
            {
                $this->testConfig = $config;
            }

            public function getConfig(): array
            {
                return $this->testConfig;
            }
        };
    }

    /**
     * 测试Text模板邮件发送
     */
    public function testSendTextMail(): void
    {
        // 检查是否配置了环境变量
        if (empty($this->config)) {
            $this->markTestSkipped('没有配置邮件环境变量，跳过测试');
        }

        $template = new Text();
        $template->setTitle('测试邮件 - Text格式')
            ->setText('这是一封测试邮件，用于测试邮件发送功能。')
            ->setPipeline(MessageNotifyInterface::INFO);

        try {
            $result = $this->mailChannel->send($template);
            $this->assertTrue($result, '邮件发送成功');
        } catch (\Throwable $e) {
            $this->fail('邮件发送失败: ' . $e->getMessage());
        }
    }

    /**
     * 测试Markdown模板邮件发送
     */
    public function testSendMarkdownMail(): void
    {
        // 检查是否配置了环境变量
        if (empty($this->config)) {
            $this->markTestSkipped('没有配置邮件环境变量，跳过测试');
        }

        $template = new Markdown();
        $template->setTitle('测试邮件 - Markdown格式')
            ->setText("## 测试内容\n\n**这是测试内容**\n\n发送时间: " . date('Y-m-d H:i:s'))
            ->setPipeline(MessageNotifyInterface::INFO);

        try {
            $result = $this->mailChannel->send($template);
            $this->assertTrue($result, '邮件发送成功');
        } catch (\Throwable $e) {
            $this->fail('邮件发送失败: ' . $e->getMessage());
        }
    }

    /**
     * 测试错误配置抛出异常.
     */
    public function testInvalidConfigThrowsException(): void
    {
        $invalidChannel = new class extends MailChannel {
            public function getConfig(): array
            {
                return [
                    'default' => MessageNotifyInterface::INFO,
                    'pipeline' => [
                        MessageNotifyInterface::INFO => [
                            // 缺少必须的配置项
                        ],
                    ],
                ];
            }
        };

        $template = new Text();
        $template->setTitle('测试标题')
            ->setText('这是测试内容')
            ->setPipeline(MessageNotifyInterface::INFO);

        $this->expectException(MessageNotificationException::class);
        $invalidChannel->send($template);
    }

    /**
     * 从.env加载邮件配置.
     */
    private function loadEnvConfig(): void
    {
        // 尝试加载.env文件
        $envFile = dirname(__DIR__) . '/.env';

        if (! file_exists($envFile)) {
            $this->config = [];
            return;
        }

        try {
            // 解析.env文件
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $env = [];
            foreach ($lines as $line) {
                // 跳过注释和无效行
                if (str_starts_with(trim($line), '#') || ! str_contains($line, '=')) {
                    continue;
                }

                [$name, $value] = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                // 移除引号
                if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                    $value = substr($value, 1, -1);
                } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
                    $value = substr($value, 1, -1);
                }

                $env[$name] = $value;
            }

            // 检查是否有邮件配置
            if (! isset($env['NOTIFY_MAIL_DSN']) || ! isset($env['NOTIFY_MAIL_FROM']) || ! isset($env['NOTIFY_MAIL_TO'])) {
                $this->config = [];
                return;
            }

            // 设置测试配置
            $this->config = [
                'default' => MessageNotifyInterface::INFO,
                'pipeline' => [
                    MessageNotifyInterface::INFO => [
                        'dsn' => $env['NOTIFY_MAIL_DSN'],
                        'from' => $env['NOTIFY_MAIL_FROM'],
                        'to' => $env['NOTIFY_MAIL_TO'],
                    ],
                    MessageNotifyInterface::ERROR => [
                        'dsn' => $env['NOTIFY_MAIL_DSN'] ?? $env['NOTIFY_MAIL_ERROR_DSN'] ?? '',
                        'from' => $env['NOTIFY_MAIL_FROM'] ?? $env['NOTIFY_MAIL_ERROR_FROM'] ?? '',
                        'to' => $env['NOTIFY_MAIL_TO'] ?? $env['NOTIFY_MAIL_ERROR_TO'] ?? '',
                    ],
                ],
            ];
        } catch (\Throwable $e) {
            $this->config = [];
        }
    }
}
