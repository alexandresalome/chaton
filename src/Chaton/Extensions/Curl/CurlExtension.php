<?php

namespace Chaton\Extensions\Curl;

use Chaton\Chaton;
use Chaton\Extension;
use Chaton\Model\Message;

class CurlExtension extends Extension
{
    const CURL_PATTERN = '/^Status code for (.*)$/';

    public function handle(Chaton $chaton, Message $message): bool
    {
        if (!preg_match(self::CURL_PATTERN, $message->getMessage(), $vars)) {
            return false;
        }

        $url = $vars[1];
        $message->reply("OK, let me check status code for ".$url);

        $chaton->httpGet($url, function($response) use ($message) {
            $message->reply("Status code is ".$response->getStatusCode());
        });

        return true;
    }
}