<?php

declare(strict_types=1);

namespace MessageNotify\Template;

class Markdown extends AbstractTemplate
{
    public function getBody(): array
    {
        return [];
    }

    public function dingTalkBody(): array
    {
        return [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $this->getTitle(),
                'text' => $this->getText(),
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
            'msg_type' => 'post',
            'content' => [
                'post' => [
                    'zh_cn' => [
                        'title' => $this->getTitle(),
                        'content' => [$this->getFeiShuText()],
                    ],
                ],
            ],
        ];
    }

    public function wechatBody(): array
    {
        return [
            'msgtype' => 'markdown',
            'markdown' => [
                'content' => $this->getTitle() . $this->getText(),
                'mentioned_list' => in_array('all', $this->getAt()) ? [] : [$this->getAt()],
                'mentioned_mobile_list' => in_array('all', $this->getAt()) ? ['@all'] : [$this->getAt()],
            ],
        ];
    }

    private function getFeiShuText(): array
    {
        $text = is_array($this->getText()) ? $this->getText() : json_decode($this->getText(), true) ?? [
            [
                'tag' => 'text',
                'text' => $this->getText(),
            ],
        ];

        $at = $this->getFeiShuAt();

        return array_merge($text, $at);
    }

    private function getFeiShuAt(): array
    {
        $result = [];
        if ($this->isAtAll()) {
            $result[] = [
                'tag' => 'at',
                'user_id' => 'all',
            ];

            return $result;
        }

        $at = $this->getAt();
        foreach ($at as $item) {
            // TODO::需要加入邮箱与收集@人
            if (strchr($item, '@') === false) {
                $result[] = [
                    'tag' => 'at',
                    'email' => $item,
                ];
            } else {
                $result[] = [
                    'tag' => 'at',
                    'user_id' => $item,
                ];
            }
        }

        return $result;
    }
}
