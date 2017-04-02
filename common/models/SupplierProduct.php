<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "supplier_product".
 *
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $product_id
 * @property string  $sku
 * @property string  $url
 * @property string  $image_downloaded
 *
 * @property Product  $product
 * @property Supplier $supplier
 */
class SupplierProduct extends ActiveRecord
{
    const IMAGE_DOWNLOADED_YES = 'yes';
    const IMAGE_DOWNLOADED_NO  = 'no';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%supplier_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id', 'sku'], 'required'],
            [['supplier_id', 'product_id'], 'integer'],
            [['sku'], 'string', 'max' => 25],
            [['url'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier_id' => 'id']],
            [['image_downloaded'], 'string'],
            [['image_downloaded'], 'default', 'value' => static::IMAGE_DOWNLOADED_NO],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'supplier_id'      => 'Supplier ID',
            'product_id'       => 'Product ID',
            'sku'              => 'Sku',
            'url'              => 'Url',
            'image_downloaded' => 'Image downloaded'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }
}
