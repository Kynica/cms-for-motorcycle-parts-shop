<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string  $name
 * @property integer $parent_id
 *
 * @property Category   $parent
 * @property Category[] $parents
 * @property Category[] $children
 *
 * @property Category[]        $parentList
 * @property CategoryClosure[] $categoryClosure
 *
 * @property Page $page
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
            'id'   => Yii::t('category', 'ID'),
            'name' => Yii::t('category', 'Name'),
            'parent_id' => Yii::t('category', 'Parent ID'),
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
                $q->groupBy('descendant');
                $q->orderBy('depth');
            });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['entity_id' => 'id'])
            ->andWhere(['controller' => static::FRONTEND_CONTROLLER]);
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
            $tree[ $id ] = $beforeName . $name;
            if (isset($allCategories[ $id ])) {
                static::createTree($allCategories, $allCategories[ $id ], $tree, $depth += 1);
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

        if ($insert)
            Page::create($this->name, static::FRONTEND_CONTROLLER, $this->id);

        if (! $insert && array_key_exists('name', $changedAttributes))
            Page::updateUrl($this->name, static::FRONTEND_CONTROLLER, $this->id);

        return true;
    }
}
