<?php

namespace frontend\components;

use Yii;
use yii\base\Object;
use yii\web\UrlRuleInterface;
use yii\web\NotFoundHttpException;
use common\models\Page;

class UrlRule extends Object implements UrlRuleInterface
{
    public function createUrl($manager, $route, $params)
    {

    }

    public function parseRequest($manager, $request)
    {
        $url = $request->getUrl();

        /** @var Page $page */
        $page = Page::find()->where(['url' => $url])->one();

        if (empty($page))
            throw new NotFoundHttpException(Yii::t('app', 'Page not found.'));

        return [
            $page->controller . '/' . 'index',
            [
                'page' => $page,
            ]
        ];
    }
}