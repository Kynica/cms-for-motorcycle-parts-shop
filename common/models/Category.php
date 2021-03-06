<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string  $name
 * @property integer $parent_id
 * @property string  $meta_title
 * @property string  $meta_description
 * @property string  $meta_keywords
 * @property string  $product_title
 * @property string  $product_description
 * @property string  $product_keywords
 * @property string  $seo_text
 *
 * @property Category                $parent
 * @property Category[]              $parents
 * @property Category[]              $children
 * @property Category[]              $parentList
 * @property Category[]              $directDescendants
 * @property CategoryClosure[]       $categoryClosure
 * @property Page                    $page
 * @property Product[]               $products
 * @property CategoryProductMargin[] $productMargin
 */
class Category extends ActiveRecord
{
    const FRONTEND_CONTROLLER = 'category';

    public $depth;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45],
            [['seo_text'], 'string'],
            [['meta_title', 'product_title'], 'string', 'max' => 250],
            [['meta_description', 'product_description'], 'string', 'max' => 500],
            [['meta_keywords', 'product_keywords'], 'string', 'max' => 200],
            [
                [
                    'seo_text',
                    'meta_title',       'product_title',
                    'meta_description', 'product_description',
                    'meta_keywords',    'product_keywords'
                ],
                'default', 'value' => null
            ],
            [['parent_id'], 'integer'],
            [['parent_id'], 'default', 'value' => null],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => Yii::t('category', 'ID'),
            'name'                => Yii::t('category', 'Name'),
            'parent_id'           => Yii::t('category', 'Parent ID'),
            'meta_title'          => Yii::t('category', 'Meta Title'),
            'meta_description'    => Yii::t('category', 'Meta Description'),
            'meta_keywords'       => Yii::t('category', 'Meta Keywords'),
            'product_title'       => Yii::t('category', 'Product Title'),
            'product_description' => Yii::t('category', 'Product Description'),
            'product_keywords'    => Yii::t('category', 'Product Keywords'),
            'seo_text'            => Yii::t('category', 'Seo Text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function _getParents()
    {
        return $this->hasMany(Category::className(), ['id' => 'ancestor'])
            ->viaTable(CategoryClosure::tableName(), ['descendant' => 'id'])
            ->select('{{%category}}.*, {{%category_closure}}.depth as depth')
            ->leftJoin(CategoryClosure::tableName(), '{{%category_closure}}.ancestor = {{%category}}.id AND {{%category_closure}}.descendant = ' . $this->id)
            ->orderBy('depth')
            ->indexBy('depth');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentList()
    {
        return $this->hasMany(Category::className(), ['id' => 'ancestor'])
            ->viaTable(CategoryClosure::tableName(), ['descendant' => 'id'])
            ->indexBy('id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryClosure()
    {
        return $this->hasMany(CategoryClosure::className(), ['descendant' => 'id'])
            ->indexBy('depth');
    }

    public function getParents()
    {
        $parents = [];
        if (! empty($this->parent_id)) {
            foreach ($this->categoryClosure as $closure) {
                $parents[ $closure->depth ] = $this->parentList[ $closure->ancestor ];
            }
        }

        return $parents;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Category::className(), ['id' => 'descendant'])
            ->viaTable(CategoryClosure::tableName(), ['ancestor' => 'id'], function ($q) {
                /** @var $q ActiveQuery */
                $q->orderBy('depth');
            });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectDescendants()
    {
        return $this->hasMany(static::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['entity_id' => 'id'])
            ->andWhere(['controller' => static::FRONTEND_CONTROLLER]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductMargin()
    {
        return $this->hasMany(CategoryProductMargin::className(), ['category_id' => 'id'])
            ->indexBy('currency_id');
    }

    public function getTotalProducts()
    {
        return (int) $this->getProducts()->count();
    }

    public function getTotalProductsWhereCurrency(Currency $currency)
    {
        return (int) $this->getProducts()
            ->where(['currency_id' => $currency->id])
            ->count();
    }

    public function getProductSellPrice(Product $product, Currency $currency)
    {
        if (! empty($this->productMargin) && array_key_exists($currency->id, $this->productMargin)) {
            $sellPrice = null;
            if (CategoryProductMargin::MARGIN_TYPE_PERCENT == $this->productMargin[ $currency->id ]->margin_type) {
                $sellPrice = $product->purchase_price + (($product->purchase_price / 100) * $this->productMargin[ $currency->id ]->margin);
            }

            if (CategoryProductMargin::MARGIN_TYPE_SUM == $this->productMargin[ $currency->id ]->margin_type) {
                $sellPrice = $product->purchase_price +  $this->productMargin[ $currency->id ]->margin;
            }

            return (float) $sellPrice;
        }

        return null;
    }

    public function getUrl()
    {
        return $this->page->url;
    }

    public static function getTreeForSelect($ignoreCategoryId = null)
    {
        /** @var static[] $categories */
        $categories = static::find()->indexBy('id')->all();

        if (count($categories) > 0) {
            foreach ($categories as $category) {
                if ($category->parent_id === null) {
                    $category->parent_id = 0;
                }
            }

            if (! empty($ignoreCategoryId)) {
                unset($categories[ $ignoreCategoryId ]);
            }

            $categories = ArrayHelper::map($categories, 'id', 'name', 'parent_id');

            return static::createTree($categories, $categories[0]);
        } else {
            return [];
        }
    }

    protected static function createTree($allCategories, $currentCategories, &$tree = [], $depth = 0)
    {
        $beforeName = '';

        for ($i = 0; $i < $depth; $i++) {
            $beforeName .= '-';
        }

        foreach ($currentCategories as $id => $name) {
            $tree[ $id ] = $beforeName . ' ' . $name;
            if (isset($allCategories[ $id ])) {
                static::createTree($allCategories, $allCategories[ $id ], $tree, $depth + 1);
            }
        }

        return $tree;
    }

    public function getParentsNames()
    {
        $r = '';
        foreach ($this->parents as $parent) {
            $r .= $parent->name . ' > ';
        }
        return $r;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (array_key_exists('parent_id', $changedAttributes)) {
            CategoryClosure::updateFor($this);
        }

        $parentUrl = empty($this->parent) ? '' : $this->parent->getUrl();

        if ($insert)
            Page::create($this->name, static::FRONTEND_CONTROLLER, $this->id, $parentUrl);

        if (! $insert && array_key_exists('name', $changedAttributes))
            Page::updateUrl($this->name, static::FRONTEND_CONTROLLER, $this->id, $parentUrl);

        return true;
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if (! empty($this->page))
            if (! $this->page->delete())
                throw new Exception('Can\'t delete category page');
    }
}
