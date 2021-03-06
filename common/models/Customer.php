<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property string  $first_name
 * @property string  $middle_name
 * @property string  $last_name
 * @property string  $phone_number
 * @property string  $email
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property integer $created_at
 *
 * @property Cart[] $carts
 */
class Customer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
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
            [['first_name', 'phone_number'], 'required'],
            [['created_at'], 'integer'],
            [['first_name', 'middle_name', 'last_name', 'phone_number'], 'string', 'max' => 45],
            [['email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['email'], 'default', 'value' => null],
            [['password_hash'], 'default', 'value' => Yii::$app->security->generateRandomString()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => Yii::t('customer', 'ID'),
            'first_name'           => Yii::t('customer', 'First Name'),
            'middle_name'          => Yii::t('customer', 'Middle Name'),
            'last_name'            => Yii::t('customer', 'Last Name'),
            'phone_number'         => Yii::t('customer', 'Phone Number'),
            'email'                => Yii::t('customer', 'Email'),
            'password_hash'        => Yii::t('customer', 'Password Hash'),
            'password_reset_token' => Yii::t('customer', 'Password Reset Token'),
            'created_at'           => Yii::t('customer', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::className(), ['customer_id' => 'id']);
    }
}
