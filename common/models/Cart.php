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
 * @property integer $updated_at
 * @property string  $is_ordered
 * @property integer $ordered_at
 * @property integer $customer_id
 * @property integer $order_status_id
 * @property integer $seller_id
 *
 * @property Product[]     $products
 * @property CartProduct[] $cartProductsData
 * @property OrderStatus   $orderStatus
 * @property UserMethods   $seller
 */
class Cart extends ActiveRecord
{
    const IS_ORDERED_YES = 'yes';
    const IS_ORDERED_NO  = 'no';

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
            [['key'], 'string', 'max' => 32],
            [['created_at', 'updated_at', 'ordered_at', 'customer_id', 'order_status_id', 'seller_id'], 'integer'],
            [['is_ordered'], 'string'],
            [['is_ordered'], 'default', 'value' => 'no'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['seller_id' => 'id']],
            [['order_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['order_status_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => Yii::t('customer', 'ID'),
            'key'             => Yii::t('customer', 'Key'),
            'created_at'      => Yii::t('customer', 'Created At'),
            'updated_at'      => Yii::t('customer', 'Updated At'),
            'is_ordered'      => Yii::t('customer', 'Is Ordered'),
            'ordered_at'      => Yii::t('customer', 'Ordered At'),
            'customer_id'     => Yii::t('customer', 'Customer ID'),
            'order_status_id' => Yii::t('customer', 'Order Status ID'),
            'seller_id'       => Yii::t('customer', 'Seller ID'),
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
    public function getCartProductsData()
    {
        return $this->hasMany(CartProduct::className(), ['cart_id' => 'id'])
            ->indexBy('product_id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'order_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(UserMethods::className(), ['id' => 'seller_id']);
    }

    public function getOrderStatusName()
    {
        if (! empty($this->orderStatus)) {
            return Yii::t('order-status', $this->orderStatus->name);
        }
        return '';
    }

    public function getSellerName()
    {
        if (! empty($this->seller)) {
            return $this->seller->getName();
        }
        return '';
    }
}
