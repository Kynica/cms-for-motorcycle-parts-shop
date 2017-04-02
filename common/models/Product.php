<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string  $sku
 * @property string  $name
 * @property string  $stock
 * @property string  $price
 * @property string  $purchase_price
 * @property string  $sell_price
 * @property string  $old_price
 * @property string  $created_at
 * @property string  $updated_at
 * @property integer $currency_id
 * @property integer $category_id
 *
 * @property Currency $currency
 * @property Category $category
 * @property Page     $page
 */
class Product extends ActiveRecord
{
    const FRONTEND_CONTROLLER = 'product';

    const STOCK_IN    = 'in';
    const STOCK_OUT   = 'out';
    const STOCK_AWAIT = 'await';

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
            [['stock'], 'default', 'value' => static::STOCK_OUT],
            [['price', 'old_price', 'purchase_price', 'sell_price'], 'number'],
            [['price', 'old_price', 'purchase_price', 'sell_price'], 'default', 'value' => '0.00'],
            [['sku', 'name'], 'string', 'max' => 255],
            [['sku', 'name'], 'trim'],
            [['currency_id', 'category_id'], 'integer'],
            [['sku', 'currency_id', 'category_id'], 'default', 'value' => NULL]
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
            'price'          => Yii::t('product', 'Site price'),
            'sell_price'     => Yii::t('product', 'Sell price'),
            'old_price'      => Yii::t('product', 'Old Price'),
            'purchase_price' => Yii::t('product', 'Purchase Price'),
            'created_at'     => Yii::t('product', 'Created At'),
            'updated_at'     => Yii::t('product', 'Updated At'),
            'currency_id'    => Yii::t('product', 'Currency'),
            'category_id'    => Yii::t('product', 'Category'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['entity_id' => 'id'])
            ->andWhere(['controller' => static::FRONTEND_CONTROLLER]);
    }

    public function getCategoryName()
    {
        if (! empty($this->category))
            return $this->category->name;
        return NULL;
    }

    public static function getStockVariation($key = null)
    {
        $variation = [
            static::STOCK_IN    => Yii::t('product', 'In Stock'),
            static::STOCK_OUT   => Yii::t('product', 'Out of Stock'),
            static::STOCK_AWAIT => Yii::t('product', 'Await')
        ];

        if (! empty($key)) {
            return $variation[$key];
        }

        return $variation;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $categoryUrl = empty($this->category) ? '' : $this->category->getUrl();

        if ($insert)
            Page::create($this->name, static::FRONTEND_CONTROLLER, $this->id, $categoryUrl);

        if (! $insert && array_key_exists('name', $changedAttributes))
            Page::updateUrl($this->name, static::FRONTEND_CONTROLLER, $this->id, $categoryUrl);

        if (! $insert && array_key_exists('category_id', $changedAttributes))
            Page::updateUrl($this->name, static::FRONTEND_CONTROLLER, $this->id, $categoryUrl);

        if (! $insert && array_key_exists('purchase_price', $changedAttributes))
            $this->updateSellPrice();

        if (! $insert && array_key_exists('sell_price', $changedAttributes))
            $this->updatePrice();

        if (array_key_exists('currency_id', $changedAttributes)) {
            $this->updateSellPrice();
            $this->updatePrice();
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if (! empty($this->page))
            if (! $this->page->delete())
                throw new Exception('Can\'t delete product page');

        File::deleteFolder(ProductImage::getStorageFolder($this));
    }

    protected function updateSellPrice()
    {
        if (! empty($this->category_id) && ! empty($this->currency_id)) {
            if (0.00 == $this->purchase_price) {
                $this->sell_price = 0.00;
            } else {
                $sellPrice = $this->category->getProductSellPrice($this, $this->currency);
                if (! empty($sellPrice)) {
                    $this->sell_price = round($sellPrice, 2);
                }
            }

            if (! $this->save())
                throw new Exception('Can\'t update product sell price.');
        }

        return;
    }

    protected function updatePrice()
    {
        if (! empty($this->currency_id)) {
            if (0.00 == $this->sell_price) {
                $this->price = $this->sell_price;
            } else {
                $this->price = round($this->sell_price * $this->currency->rate, 2);
            }

            if (! $this->save())
                throw new Exception('Can\'t update product price.');
        }

        return;
    }
}
