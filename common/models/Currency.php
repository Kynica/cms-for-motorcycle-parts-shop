<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "currency".
 *
 * @property integer $id
 * @property string  $code
 * @property string  $name
 * @property string  $symbol
 * @property string  $rate
 *
 * @property Product[] $products
 */
class Currency extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'symbol'], 'required'],
            [['code', 'name', 'symbol'], 'string', 'max' => 25],
            [['rate'], 'number'],
            [['rate'], 'default', 'value' => '1.00'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'     => Yii::t('currency', 'ID'),
            'code'   => Yii::t('currency', 'Code'),
            'name'   => Yii::t('currency', 'Name'),
            'symbol' => Yii::t('currency', 'Symbol'),
            'rate'   => Yii::t('currency', 'Rate'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['currency_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (! $insert && array_key_exists('rate', $changedAttributes))
            $this->updateProductPrice();
    }

    protected function updateProductPrice()
    {
        $db = Yii::$app->db;

        $db->createCommand()
            ->update(Product::tableName(),
                [
                    'price' => new Expression("sell_price * {$this->rate}")
                ],
                "currency_id = {$this->id} AND price != '0.00'"
            )->execute();

        return;
    }
}
