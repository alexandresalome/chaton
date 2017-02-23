<?php

namespace Chaton\Extensions\Curl;

use Chaton\Chaton;
use Chaton\Extension;
use Chaton\Model\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use WyriHaximus\React\GuzzlePsr7\HttpClientAdapter;

class CurlExtension extends Extension
{
    const CURL_PATTERN = '/^Status code for (.*)$/';

    public function register(Chaton $chaton)
    {
        $handler = new HttpClientAdapter($chaton['loop']);
        $chaton['guzzle'] = new Client(['handler' => HandlerStack::create($handler)]);
    }

    public function handle(Chaton $chaton, Message $message): bool
    {
        if (!preg_match(self::CURL_PATTERN, $message->getMessage(), $vars)) {
            return false;
        }

        $url = $vars[1];
        $message->reply("OK, let me check status code for ".$url);

        $chaton['guzzle']->getAsync($url)->then(function($response) use ($message) {
            $message->reply("Status code is ".$response->getStatusCode());
        }, function (\Exception $error) use ($message) {
            if ($error->getPrevious()) {
                $error = $error->getPrevious();
            }
            $message->reply("An error occurred during HTTP call: ".$error->getMessage());
        });

        return true;
    }
}
