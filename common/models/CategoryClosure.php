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
}
