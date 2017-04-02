<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category_product_margin".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $currency_id
 * @property string  $margin_type
 * @property string  $margin
 *
 * @property Currency $currency
 * @property Category $category
 */
class CategoryProductMargin extends ActiveRecord
{
    const MARGIN_TYPE_SUM     = 'sum';
    const MARGIN_TYPE_PERCENT = 'percent';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_product_margin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'currency_id'], 'required'],
            [['category_id', 'currency_id'], 'integer'],
            [['margin_type'], 'string'],
            [['margin'], 'number'],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['margin_type'], 'default', 'value' => static::MARGIN_TYPE_PERCENT],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('category-product-margin', 'ID'),
            'category_id' => Yii::t('category-product-margin', 'Category ID'),
            'currency_id' => Yii::t('category-product-margin', 'Currency ID'),
            'margin_type' => Yii::t('category-product-margin', 'Margin Type'),
            'margin'      => Yii::t('category-product-margin', 'Margin'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
