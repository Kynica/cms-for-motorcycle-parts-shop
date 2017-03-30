<?php

namespace common\components;

use Yii;
use yii\base\Exception;
use yii\imagine\Image;
use Imagine\Image\Box;

class ImageCache
{
    public static function create($imagePath, $width, $height, $quality = 100, $scope = 'global')
    {
        return static::toCache($scope, $imagePath, $width, $height, $quality);
    }

    protected static function toCache($scope, $imagePath, $width, $height, $quality)
    {
        $fullCachePath  = static::getFullCachePath($scope);
        $cacheImageName = static::getImageCacheName(static::getFromPath($imagePath, 'name'), $width, $height, $quality);

        if (! file_exists($fullCachePath . '/' . $cacheImageName)) {
            $frontendPath = static::getImageFrontendPath($imagePath);
            Image::getImagine()
                ->open($frontendPath)
                ->thumbnail(new Box($width, $height))
                ->save($fullCachePath . '/' . $cacheImageName, ['quality' => $quality]);
        }
        return '/' . Yii::$app->params['imageCacheFolder'] . '/' . $scope . '/' . $cacheImageName;
    }

    protected static function getFromPath($imagePath, $what = 'name')
    {
        $path = explode('/', $imagePath);
        $name = array_pop($path);

        switch ($what) {
            case 'name': return $name; break;
            case 'path': return implode('/', $path); break;
            default: throw new Exception('Chose what return name or path');
        }
    }

    protected static function getFullCachePath($scope)
    {
        $cacheFolder = Yii::getAlias('@webroot') . '/' . Yii::$app->params['imageCacheFolder'] . '/' . $scope;
        if (! file_exists($cacheFolder)) {
            if (! mkdir($cacheFolder, 0755, true))
                throw new Exception('Cant create cache folder');
        }
        return $cacheFolder;
    }

    protected static function getImageCacheName($imageName, $width, $height, $quality)
    {
        return $width . 'x' . $height . 'x' . $quality . '-'. $imageName;
    }

    protected static function getImageFrontendPath($imagePath)
    {
        return Yii::getAlias('@frontend') . '/web/' . $imagePath;
    }
}