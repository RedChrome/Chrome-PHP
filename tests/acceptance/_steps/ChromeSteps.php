<?php
namespace WebGuy;

class ChromeSteps extends \WebGuy
{
    public function speakLanguage($language = 'xx-XX')
    {
        $this->executeInGuzzle(function (\Guzzle\Http\Client $client) use ($language) {
            $client->setDefaultHeaders(array('Accept-Language' => $language));
        });
    }
}