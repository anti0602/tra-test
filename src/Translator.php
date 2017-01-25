<?php

namespace App\Services\GoogleTranslate\src;


use GuzzleHttp\Client;

class Translator
{
    private static $baseLink = 'https://translate.google.com/translate_a/single';

    private static $baseParams = [
        'client' => 'webapp',
        'sl' => 'auto',
        'tl' => 'ru',
        'hl' => 'ru',
        'dj' => 1,
        'ie' => 'UTF-8',
        'oe' => 'UTF-8',
    ];

    private $token = '';

    public static function translate($text)
    {
        $token = Token::generate($text, "412320.3361919554");
        $client = new Client();
        $params = array_merge(self::$baseParams, [
            'tk' => $token,
            'q' => urlencode($text)
        ]);
        $r = $client->get(self::$baseLink, ['query' => http_build_query($params).'&dt=bd&dt=ld&dt=qc&dt=rm&dt=t'])->getBody()->getContents();
        return json_decode($r);
    }
}