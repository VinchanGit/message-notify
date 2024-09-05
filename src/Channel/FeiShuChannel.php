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

class FeiShuChannel extends AbstractChannel
{
    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function send(AbstractTemplate $template): bool
    {
        $client = $this->getClient($template->getPipeline());

        $timestamp = time();
        $config = [
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp, $template->getPipeline()),
        ];

        $option = [
            RequestOptions::HEADERS => [],
            RequestOptions::JSON => array_merge($config, $template->feiShuBody()),
        ];

        $request = $client->post('', $option);
        $result = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (! isset($result['StatusCode']) || $result['StatusCode'] !== 0) {
            throw new MessageNotificationException($result['msg']);
        }

        return true;
    }

    public function getClient(string $pipeline)
    {
        $config = $this->config($pipeline);

        $uri['base_uri'] = 'https://open.feishu.cn/open-apis/bot/v2/hook/' . $config['token'];

        if (class_exists(ApplicationContext::class)) {
            return make(Client::class, [$uri]);
        }

        return new Client($config);
    }

    private function getSign(int $timestamp, string $pipeline): string
    {
        $config = $this->config($pipeline);
        $secret = hash_hmac('sha256', '', $timestamp . "\n" . $config['secret'], true);
        return base64_encode($secret);
    }

    private function config(string $pipeline)
    {
        $config = $this->getConfig();
        return $config['pipeline'][$pipeline] ?? $config['pipeline'][$config['default']];
    }
}
