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

class DingTalkChannel extends AbstractChannel
{
    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function send(AbstractTemplate $template): bool
    {
        $query = $this->getQuery($template->getPipeline());

        $client = $this->getClient($query);

        $option = [
            RequestOptions::HEADERS => [],
            RequestOptions::JSON => $template->dingTalkBody(),
        ];
        $request = $client->post('', $option);
        $result = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if ($result['errcode'] !== 0) {
            throw new MessageNotificationException($result['errmsg']);
        }

        return true;
    }

    public function getClient(string $query)
    {
        $config['base_uri'] = 'https://oapi.dingtalk.com/robot/send' . $query;

        if (class_exists(ApplicationContext::class)) {
            return make(Client::class, [$config]);
        }

        return new Client($config);
    }

    private function getQuery(string $pipeline): string
    {
        $timestamp = time() * 1000;

        $config = $this->getConfig();
        $config = $config['pipeline'][$pipeline] ?? $config['pipeline'][$config['default']];

        $secret = hash_hmac('sha256', $timestamp . "\n" . $config['secret'], $config['secret'], true);
        $sign = urlencode(base64_encode($secret));
        return "?access_token={$config['token']}&timestamp={$timestamp}&sign={$sign}";
    }
}
