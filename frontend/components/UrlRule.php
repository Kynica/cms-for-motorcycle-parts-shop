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
        switch ($route) {
            case 'category/index':
                return $this->createCategoryUrl($params);
                break;
            default:
                return '';
                break;
        }
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

    protected function createCategoryUrl($params)
    {
        $url = Yii::$app->request->getUrl();
        if (array_key_exists('page', $params)) {
            if ($params['page'] == 1) {
                return $url;
            } else {
                return $url . '/filter:page=' . $params['page'];
            }
        }
        return '';
    }
}