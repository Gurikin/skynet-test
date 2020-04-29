<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 30.04.2020 0:56
 */


class Response
{
    public const STATUS_OK = 200;

    public const TYPE_JSON = 'application/json';
    public const TYPE_HTML = 'text/html';


    public static function send(string $content, string $type = self::TYPE_HTML, int $status = self::STATUS_OK)
    {
        header('Content-Type: ' . $type);
        header('Status: ' . $status);
        echo $content;
    }
}