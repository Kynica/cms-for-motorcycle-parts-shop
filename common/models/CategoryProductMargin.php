<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
            [['margin'], 'default', 'value' => '1.00'],
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

    public static function getMargins(Category $category)
    {
        /** @var Currency[] $currency */
        $currency = Currency::find()->all();
        $margins  = static::find()
            ->where(['category_id' => $category->id])
            ->indexBy('currency_id')
            ->all();

        foreach ($currency as $c) {
            if (! isset($margins[ $c->id ])) {
                $margins[ $c->id ] = new static([
                    'category_id' => $category->id,
                    'currency_id' => $c->id,
                ]);

                if (! $margins[ $c->id ]->save())
                    throw new Exception('Can\'t save new category product margin.');
            }
        }

        return $margins;
    }

    public static function updateMargins(Category $category, $marginsData)
    {
        /** @var static[] $margins */
        $margins = static::find()
            ->where(['category_id' => $category->id])
            ->indexBy('id')
            ->all();

        foreach ($margins as $margin) {
            if (array_key_exists($margin->id, $marginsData)) {
                $margin->setAttributes($marginsData[ $margin->id ]);

                if (! $margin->save())
                    throw new Exception('Can\'t save category product margin');
            }
        }

        return;
    }

    public static function getMarginTypes()
    {
        return [
            static::MARGIN_TYPE_PERCENT => 'Percent',
            static::MARGIN_TYPE_SUM     => 'Sum',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (! $insert && count($changedAttributes) > 0)
            $this->updateCategoryProductPrice($this->category);
    }

    protected function updateCategoryProductPrice(Category $category)
    {
        if ($category->getTotalProductsWhereCurrency($this->currency)) {
            $db = Yii::$app->db;

            if (static::MARGIN_TYPE_PERCENT == $this->margin_type) {
                $db->createCommand()
                    ->update(
                        Product::tableName(),
                        [
                            'sell_price' => new Expression("(purchase_price + ((purchase_price / 100) * {$this->margin}))")
                        ],
                        "currency_id = {$this->currency->id} AND purchase_price != '0.00'"
                    )->execute();
            }

            if (static::MARGIN_TYPE_SUM == $this->margin_type) {
                $db = Yii::$app->db;

                $db->createCommand()
                    ->update(
                        Product::tableName(),
                        [
                            'sell_price' => new Expression("(purchase_price + {$this->margin})")
                        ],
                        "currency_id = {$this->currency->id} AND purchase_price != '0.00'"
                    )->execute();
            }

            $db->createCommand()
                ->update(
                    Product::tableName(),
                    [
                        'price' => new Expression("(sell_price * {$this->currency->rate})")
                    ],
                    "currency_id = {$this->currency->id} AND sell_price != '0.00'"
                )->execute();

            return;
        }
    }
}
