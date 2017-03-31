<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use common\models\File;

/**
 * This is the model class for table "supplier".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $code
 */
class Supplier extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%supplier}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'code'], 'string', 'max' => 45],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'         => SluggableBehavior::className(),
                'attribute'     => 'name',
                'slugAttribute' => 'code'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'   => Yii::t('supplier', 'ID'),
            'name' => Yii::t('supplier', 'Name'),
            'code' => Yii::t('supplier', 'Code'),
        ];
    }

    public function getStorageFolder()
    {
        return '/' . Yii::$app->params['uploadDir'] . '/' . 'supplier/price';
    }

    public function getPriceUrl()
    {
        $path = $this->getStorageFolder() . '/' . $this->code . '.csv';
        if (File::exist($path)) {
            return [
                $path
            ];
        } else {
            return null;
        }
    }

    public function getPriceData()
    {
        $path = $this->getStorageFolder() . '/' . $this->code . '.csv';
        if (File::exist($path)) {
            return [
                [
                    'type' => 'text',
                    'filetype' => 'text/html',
                    'url'  => 'fsdfsdf',
                    'caption' => 'fsfsdf',
                    'previewAsData' => true
                ]
            ];
        }
        return [];
    }

    public function uploadPrice()
    {
        File::uploadFile($this->getStorageFolder(), $this->code);

        return;
    }
}
