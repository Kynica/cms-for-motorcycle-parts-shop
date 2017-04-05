<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property string  $key
 * @property integer $created_at
 * @property integer $order_id
 * @property integer $customer_id
 *
 * @property Product[]     $products
 * @property CartProduct[] $cartProductData
 */
class Cart extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['created_at', 'updated_at', 'order_id', 'customer_id'], 'integer'],
            [['key'], 'string', 'max' => 32],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['order_id', 'customer_id'], 'default', 'value' => null],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('cart', 'ID'),
            'key'         => Yii::t('cart', 'Key'),
            'created_at'  => Yii::t('cart', 'Created At'),
            'order_id'    => Yii::t('cart', 'Order Id'),
            'customer_id' => Yii::t('cart', 'Customer Id'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
            ->viaTable(CartProduct::tableName(), ['cart_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCartProductData()
    {
        return $this->hasMany(CartProduct::className(), ['cart_id' => 'id'])
            ->indexBy('product_id');
    }
}
