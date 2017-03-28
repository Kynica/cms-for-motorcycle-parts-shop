<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string  $name
 * @property integer $parent_id
 */
class Category extends ActiveRecord
{
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
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
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

    public static function getTreeForSelect($ignoreCategoryId = null)
    {
        /** @var static[] $categories */
        $categories = static::find()->indexBy('id')->all();

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
}
