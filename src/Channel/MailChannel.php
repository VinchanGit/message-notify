<?php

declare(strict_types=1);
/**
 * Copyright (c) The Vinchan , Distributed under the software license
 */

namespace MessageNotify\Channel;

use MessageNotify\Exceptions\MessageNotificationException;
use MessageNotify\Template\AbstractTemplate;
use MessageNotify\Template\Markdown as MarkdownTemplate;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class MailChannel extends AbstractChannel
{
    /**
     * 发送邮件通知.
     */
    public function send(AbstractTemplate $template): bool
    {
        $config = $this->getConfig();
        $config = $config['pipeline'][$template->getPipeline()] ?? $config['pipeline'][$config['default']];

        if (empty($config['dsn']) || empty($config['from']) || empty($config['to'])) {
            throw new MessageNotificationException('Mail configuration is incomplete');
        }

        try {
            $transport = Transport::fromDsn($config['dsn']);
            $mailer = new Mailer($transport);

            $email = new Email();
            $email->from($config['from'])
                ->to(...(is_array($config['to']) ? $config['to'] : [$config['to']]))
                ->subject($template->getTitle());

            // 根据模板类型设置邮件内容
            if ($template instanceof MarkdownTemplate) {
                // 对于Markdown模板，使用HTML格式
                $email->html($template->getText());
            } else {
                // 对于其他模板类型，使用纯文本格式
                $email->text($template->getText());
            }

            $mailer->send($email);
            return true;
        } catch (\Throwable $e) {
            throw new MessageNotificationException('Failed to send email: ' . $e->getMessage());
        }
    }
}
