<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string  $sku
 * @property string  $name
 * @property string  $stock
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
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['stock'], 'string'],
            [['stock'], 'default', 'value' => 'out'],
            [['price', 'old_price', 'purchase_price'], 'number'],
            [['price', 'old_price', 'purchase_price'], 'default', 'value' => '0.00'],
            [['sku', 'name'], 'string', 'max' => 255],
            ['sku', 'default', 'value' => NULL]
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
            'stock'          => Yii::t('product', 'Stock'),
            'price'          => Yii::t('product', 'Price'),
            'old_price'      => Yii::t('product', 'Old Price'),
            'purchase_price' => Yii::t('product', 'Purchase Price'),
            'created_at'     => Yii::t('product', 'Created At'),
            'updated_at'     => Yii::t('product', 'Updated At'),
        ];
    }

    public function getStockVariation()
    {
        return [
            'in'    => Yii::t('product', 'In Stock'),
            'out'   => Yii::t('product', 'Out of Stock'),
            'await' => Yii::t('product', 'Await')
        ];
    }
}
