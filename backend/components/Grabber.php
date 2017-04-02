<?php

namespace backend\components;

use yii\base\Exception;

class Grabber
{
    public static function getImageAndSave($url, $savePath)
    {
        $fp = fopen ($savePath, 'w+');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36');
        curl_setopt($ch, CURLOPT_VERBOSE, false);   // Enable this line to see debug prints
        curl_exec($ch);

        curl_close($ch);
        fclose($fp);

        $image    = new \Imagick($savePath);
        $mimeType = $image->getImageMimeType();

        if ($mimeType != 'image/jpeg') {
            unlink($savePath);
            throw new Exception('Wrong image format');
        }

        return true;
    }

    public static function isPageExist($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
        $output   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode == 200 ? true : false;
    }
}