<?php

namespace D4rk0s\WpMatomoAPI\Toolbox;

trait MatomoToolbox
{
    /**
     * @todo: Probleme de sécurité avec le SSL.
     * @param array $postData
     * @throws \JsonException
     * @return array
     */
    private static function executeQuery(array $postData) : array
    {
        $url = Config::get("MATOMO_URL");
        $postData['module'] = 'API';
        $postData['format'] = 'JSON';
        $postData['token_auth'] = Config::get("MATOMO_API_KEY");

        $opts = array(
            'http' =>
                [
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query($postData)
                ],
            'ssl' => [
                'verify_peer' => false
            ]
        );

        $context = stream_context_create($opts);

        return json_decode(file_get_contents($url, false, $context), true, 512, JSON_THROW_ON_ERROR);
    }

}