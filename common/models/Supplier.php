<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "supplier".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $code
 * @property string  $site
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
            [['name', 'code', 'site'], 'string', 'max' => 45],
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
            'site' => Yii::t('supplier', 'Site'),
        ];
    }

    public function getStorageFolder()
    {
        return '/' . Yii::$app->params['uploadDir'] . '/' . 'supplier/price';
    }

    public function getPriceLink()
    {
        return $this->getStorageFolder() . '/' . $this->code . '.csv';
    }
    //TODO something width this two methods.
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
                    'caption' => $this->code . '.csv',
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
