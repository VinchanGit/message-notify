<?php

declare(strict_types=1);

namespace MessageNotify\Channel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Hyperf\Context\ApplicationContext;
use MessageNotify\Exceptions\MessageNotificationException;
use MessageNotify\Template\AbstractTemplate;

use function Hyperf\Support\make;

class WechatChannel extends AbstractChannel
{
    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function send(AbstractTemplate $template): bool
    {
        $client = $this->getClient($template->getPipeline());

        $option = [
            RequestOptions::HEADERS => [],
            RequestOptions::JSON => $template->wechatBody(),
        ];
        $request = $client->post('', $option);
        $result = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if ($result['errcode'] !== 0) {
            throw new MessageNotificationException($result['errmsg']);
        }

        return true;
    }

    private function getClient(string $pipeline)
    {
        $config = $this->config($pipeline);

        $uri['base_uri'] = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=' . $config['token'];

        if (class_exists(ApplicationContext::class)) {
            return make(Client::class, [$uri]);
        }

        return new Client($config);
    }

    private function config(string $pipeline)
    {
        $config = $this->getConfig();
        return $config['pipeline'][$pipeline] ?? $config['pipeline'][$config['default']];
    }
}
