<?php

declare(strict_types=1);

namespace MessageNotifyTest;

use MessageNotify\Channel\DingTalkChannel;
use MessageNotify\Channel\FeiShuChannel;
use MessageNotify\Channel\WechatChannel;
use MessageNotify\Contracts\MessageNotifyInterface;
use MessageNotify\Notify;
use MessageNotify\Template\Markdown;
use MessageNotify\Template\Text;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class NotifyTest extends TestCase
{
    public function testCase(): void
    {
        $dingTalkChannel = new DingTalkChannel();
        $feiShuChannel = new FeiShuChannel();
        $wechatChannel = new WechatChannel();

        $markdown = new Markdown();
        $text = new Text();

        $notify = Notify::make()->setChannel(DingTalkChannel::class)
            ->setAt(['all'])
            ->setTitle('标题')
            ->setText('测试')
            ->setPipeline(MessageNotifyInterface::INFO)
            ->setTemplate(Markdown::class)
            ->send();

        $this->assertTrue($notify);
    }
}
