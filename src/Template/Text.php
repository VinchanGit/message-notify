<?php

declare(strict_types=1);

namespace MessageNotify\Template;

class Text extends AbstractTemplate
{
    public function getBody(): array
    {
        return [];
    }

    public function dingTalkBody(): array
    {
        return [
            'msgtype' => 'text',
            'text' => [
                'content' => $this->getText(),
            ],
            'at' => [
                'isAtAll' => $this->isAtAll(),
                'atMobiles' => $this->getAt(),
            ],
        ];
    }

    public function feiShuBody(): array
    {
        return [
            'msg_type' => 'text',
            'content' => [
                'text' => $this->getText() . $this->getFeiShuAt(),
            ],
        ];
    }

    public function wechatBody(): array
    {
        return [
            'msgtype' => 'text',
            'text' => [
                'content' => $this->getText(),
                'mentioned_list' => in_array('all', $this->getAt()) ? [] : [$this->getAt()],
                'mentioned_mobile_list' => in_array('all', $this->getAt()) ? ['@all'] : [$this->getAt()],
            ],
        ];
    }

    private function getFeiShuAt(): string
    {
        if ($this->isAtAll()) {
            return '<at user_id="all">所有人</at>';
        }

        $at = $this->getAt();
        $result = '';
        foreach ($at as $item) {
            if (! str_contains($item, '@')) {
                $result .= '<at phone="' . $item . '">' . $item . '</at>';
            } else {
                $result .= '<at email="' . $item . '">' . $item . '</at>';
            }
        }
        return $result;
    }
}
