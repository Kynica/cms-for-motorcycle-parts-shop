<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_status".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $slug
 * @property integer $priority
 *
 * @property Order[] $orders
 */
class OrderStatus extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['priority'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => Yii::t('order-status', 'ID'),
            'name'     => Yii::t('order-status', 'Name'),
            'slug'     => Yii::t('order-status', 'Slug'),
            'priority' => Yii::t('order-status', 'Priority'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['status_id' => 'id']);
    }
}
