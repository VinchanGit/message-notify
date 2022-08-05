<?php

declare(strict_types=1);

namespace MessageNotify;

use MessageNotify\Channel\AbstractChannel;
use MessageNotify\Contracts\MessageNotifyInterface;
use MessageNotify\Exceptions\MessageNotificationException;
use MessageNotify\Template\AbstractTemplate;
use MessageNotify\Template\Text;

class Client
{
    protected AbstractChannel $channel;

    protected AbstractTemplate $template;

    protected array $at = [];

    protected string $pipeline = MessageNotifyInterface::INFO;

    protected string $title = '';

    protected string $text = '';

    private string $errorMessage;

    public function getChannel(): AbstractChannel
    {
        return $this->channel;
    }

    public function getTemplate(): AbstractTemplate
    {
        return $this->template ?? new Text();
    }

    public function getAt(): array
    {
        return $this->at;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setChannel(AbstractChannel $channel): Client
    {
        $this->channel = $channel;
        return $this;
    }

    public function setTemplate(AbstractTemplate $template): Client
    {
        $this->template = $template;
        return $this;
    }

    public function getPipeline(): string
    {
        return $this->pipeline;
    }

    public function setPipeline(string $pipeline): Client
    {
        $this->pipeline = $pipeline;
        return $this;
    }

    public function setAt(array $at = []): Client
    {
        $this->at = $at;
        return $this;
    }

    public function setTitle(string $title = ''): Client
    {
        $this->title = $title;
        return $this;
    }

    public function setText(string $text = ''): Client
    {
        $this->text = $text;
        return $this;
    }

    public function send(): bool
    {
        try {
            $template = $this->getTemplate()->setAt($this->getAt())
                ->setTitle($this->getTitle())->setText($this->getText())
                ->setPipeline($this->getPipeline());

            $this->getChannel()->send($template);
            return true;
        } catch (MessageNotificationException $exception) {
            $this->errorMessage = $exception->getMessage();
            return false;
        }
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
