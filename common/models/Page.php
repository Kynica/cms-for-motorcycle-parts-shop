<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

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

    public static function create($url, $controller, $entityId)
    {
        $page = new static([
            'url'        => static::normalizeUrl($url),
            'controller' => $controller,
            'entity_id'  => $entityId
        ]);

        if (! $page->save())
            throw new Exception('Can\'t save page for ' . $controller . ' with entity id ' . $entityId);

        return;
    }

    public static function updateUrl($newUrl, $controller, $entityId)
    {
        /** @var static $page */
        $page = static::find()->where(['controller' => $controller, 'entity_id' => $entityId])->one();

        if (empty($page))
            throw new Exception('Page not exist. Pls, create page before update it.');

        $page->url = static::normalizeUrl($newUrl);

        if (! $page->save())
            throw new Exception('Can\'t update page for ' . $controller . ' with entity id ' . $entityId);

        return;
    }

    public static function normalizeUrl($url)
    {
        return '/' . Inflector::slug('/' . $url);
    }
}
