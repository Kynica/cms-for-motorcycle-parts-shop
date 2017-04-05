<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cart_product".
 *
 * @property integer $id
 * @property integer $cart_id
 * @property integer $product_id
 * @property integer $quantity
 * @property integer $added_at
 *
 * @property Product $product
 * @property Cart    $cart
 */
class CartProduct extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart_product}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'added_at',
                'updatedAtAttribute' => false,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cart_id', 'product_id'], 'required'],
            [['cart_id', 'product_id', 'added_at', 'quantity'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::className(), 'targetAttribute' => ['cart_id' => 'id']],
            [['quantity'], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('cart-product', 'ID'),
            'cart_id'    => Yii::t('cart-product', 'Cart ID'),
            'product_id' => Yii::t('cart-product', 'Product ID'),
            'quantity'   => Yii::t('cart-product', 'Quantity'),
            'added_at'   => Yii::t('cart-product', 'Added At'),
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
    public function getCart()
    {
        return $this->hasOne(Cart::className(), ['id' => 'cart_id']);
    }
}
