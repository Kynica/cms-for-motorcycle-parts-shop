<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;

class File extends Model
{
    const SCENARIO_UPLOAD_IMAGE = 'uploadImage';

    public $upload;

    public function rules()
    {
        return [
            [['upload'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 20, 'on' => static::SCENARIO_UPLOAD_IMAGE],
        ];
    }

    public static function uploadImages($filePath, $imageNumber, $newName = null)
    {
        $fullPath          = Yii::getAlias('@frontend') . '/web' . $filePath;
        if (! FileHelper::createDirectory($fullPath))
            throw new Exception('Can\'t create folder "' . $fullPath . '" for image');
        $newFile           = new static();
        $newFile->scenario = static::SCENARIO_UPLOAD_IMAGE;
        $newFile->upload   = UploadedFile::getInstances($newFile, 'upload');

        $newImages = [];

        if ($newFile->validate()) {
            foreach ($newFile->upload as $sort => $image) {
                $name = empty($newName) ? $image->baseName : $newName;
                $name = Inflector::slug($name . '-' . ++$imageNumber) . '.' . $image->extension;

                if (! $image->saveAs($fullPath . '/' . $name))
                    throw new Exception('Can\'t save image "' . $name . '" to folder "' . $fullPath . '"');

                $newImages[] = $name;
            }
        } else {
            throw new Exception('Images no pass validation.');
        }
        return $newImages;
    }

    public static function uploadFile($filePath, $newName = null)
    {
        $fullPath          = Yii::getAlias('@backend') . '/web' . $filePath;
        if (! FileHelper::createDirectory($fullPath))
            throw new Exception('Can\'t create folder "' . $fullPath . '" for image');
        $newFile           = new static();
        $newFile->upload   = UploadedFile::getInstance($newFile, 'upload');

        $name = empty($newName) ? $newFile->upload->baseName : $newName;
        $name .= '.' . $newFile->upload->extension;

        if (! $newFile->upload->saveAs($fullPath . '/' . $name))
            throw new Exception('Can\'t save file "' . $name . '" to folder "' . $fullPath . '"');

        return $name;
    }

    public static function exist($filePath)
    {
        $fullFilePath = Yii::getAlias('@backend') . '/web' . $filePath;
        $ex = file_exists($fullFilePath);
        return $ex;
    }

    public static function delete($filePath)
    {
        $fullFilePath = Yii::getAlias('@frontend') . '/web' . $filePath;
        return unlink($fullFilePath);
    }

    public static function deleteFolder($path)
    {
        $fullFilePath = Yii::getAlias('@frontend') . '/web' . $path;
        FileHelper::removeDirectory($fullFilePath);
    }

    public static function getFullPath($path, $alias = '@webroot')
    {
        $fullPath = Yii::getAlias($alias) . $path;
        return $fullPath;
    }
}