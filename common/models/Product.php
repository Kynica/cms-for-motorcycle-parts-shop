<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string  $sku
 * @property string  $name
 * @property string  $price
 * @property string  $old_price
 * @property string  $purchase_price
 * @property string  $created_at
 * @property string  $updated_at
 */
class Product extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_at'], 'required'],
            [['price', 'old_price', 'purchase_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['sku', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => Yii::t('product', 'ID'),
            'sku'            => Yii::t('product', 'Sku'),
            'name'           => Yii::t('product', 'Name'),
            'price'          => Yii::t('product', 'Price'),
            'old_price'      => Yii::t('product', 'Old Price'),
            'purchase_price' => Yii::t('product', 'Purchase Price'),
            'created_at'     => Yii::t('product', 'Created At'),
            'updated_at'     => Yii::t('product', 'Updated At'),
        ];
    }
}
