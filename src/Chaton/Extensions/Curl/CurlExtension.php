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
        if (preg_match(self::CURL_PATTERN, $message->getMessage(), $vars)) {
            $url = $vars[1];

            $chaton->httpGet($url, function($response) use ($message) {
                $message->reply();
            });

            return true;
        }
    }
}