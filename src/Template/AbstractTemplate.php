<?php

declare(strict_types=1);

namespace MessageNotify\Template;

use MessageNotify\Contracts\MessageNotifyInterface;

abstract class AbstractTemplate
{
    protected array $at = [];

    protected string $pipeline = MessageNotifyInterface::INFO;

    protected string $text = '';

    protected string $title = '';

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): AbstractTemplate
    {
        $this->text = $text;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): AbstractTemplate
    {
        $this->title = $title;
        return $this;
    }

    public function getPipeline(): string
    {
        return $this->pipeline;
    }

    public function setPipeline(string $pipeline): AbstractTemplate
    {
        $this->pipeline = $pipeline;
        return $this;
    }

    public function setAt(array $at = []): AbstractTemplate
    {
        $this->at = $at;
        return $this;
    }

    public function getAt(): array
    {
        return $this->at;
    }

    public function isAtAll(): bool
    {
        return in_array('all', $this->at) || in_array('ALL', $this->at);
    }

    abstract public function getBody();
}
