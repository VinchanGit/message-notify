<?php

declare(strict_types=1);

namespace MessageNotifyTest;

use MessageNotify\Channel\DingTalkChannel;
use MessageNotify\Channel\FeiShuChannel;
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
    public function testCase()
    {
        $dingTalkChannel = new DingTalkChannel();
        $feiShuChannel = new FeiShuChannel();
        $markdown = new Markdown();
        $text = new Text();

        $notify = Notify::make()->setChannel($feiShuChannel)
            ->setAt(['all'])
            ->setTitle('标题')->setText('内容')
            ->setPipeline(MessageNotifyInterface::INFO)
            ->setTemplate($markdown)
            ->send();

        $this->assertEquals($notify, true);
    }
}
