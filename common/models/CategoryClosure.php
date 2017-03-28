<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category_closure".
 *
 * @property integer $id
 * @property integer $ancestor
 * @property integer $descendant
 * @property integer $depth
 */
class CategoryClosure extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_closure}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ancestor', 'descendant', 'depth'], 'required'],
            [['ancestor', 'descendant', 'depth'], 'integer'],
            [['descendant'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['descendant' => 'id']],
            [['ancestor'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['ancestor' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ancestor' => 'Ancestor',
            'descendant' => 'Descendant',
            'depth' => 'Depth',
        ];
    }

    public static function updateFor(Category $category)
    {
        static::deleteFor($category);
        static::createFor($category);
        static::updateForChildren($category);

        return;
    }

    protected static function createFor(Category $category)
    {
        if (! empty($category->parent_id)) {
            /** @var Category $parentCategory */
            $parentCategory = Category::find()
                ->with(['parents'])
                ->where(['id' => $category->parent_id])
                ->one();

            $depth = 0;

            foreach ($parentCategory->parents as $parent) {
                $new = new CategoryClosure([
                    'ancestor'   => $parent->id,
                    'descendant' => $category->id,
                    'depth'      => $depth
                ]);

                $new->save();

                $depth++;
            }

            $new = new CategoryClosure([
                'ancestor'   => $parentCategory->id,
                'descendant' => $category->id,
                'depth'      => $depth
            ]);

            $new->save();
        }

        return;
    }

    protected static function deleteFor(Category $category)
    {
        Yii::$app->db->createCommand()
            ->delete(CategoryClosure::tableName(), ['descendant' => $category->id])
            ->execute();
    }

    protected static function updateForChildren(Category $category)
    {
        foreach ($category->children as $child) {
            static::deleteFor($child);
            static::createFor($child);

            if (count($child->children))
                static::updateForChildren($child);
        }
    }
}
