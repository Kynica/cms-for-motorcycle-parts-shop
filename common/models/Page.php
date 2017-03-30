<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string  $url
 * @property string  $controller
 * @property integer $entity_id
 */
class Page extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'controller', 'entity_id'], 'required'],
            [['entity_id'], 'integer'],
            [['url'], 'string', 'max' => 500],
            [['controller'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('page', 'ID'),
            'url'        => Yii::t('page', 'Url'),
            'controller' => Yii::t('page', 'Controller'),
            'entity_id'  => Yii::t('page', 'Entity ID'),
        ];
    }
}
