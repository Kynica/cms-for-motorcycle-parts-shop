<?php

namespace frontend\components;

use Yii;
use yii\base\Object;
use yii\web\UrlRuleInterface;
use yii\web\NotFoundHttpException;
use common\models\Page;

class UrlRule extends Object implements UrlRuleInterface
{
    public $filter;

    public function createUrl($manager, $route, $params)
    {
        switch ($route) {
            case 'category/index':
                return $this->createCategoryUrl($params);
            default:
                return '';
        }
    }

    public function parseRequest($manager, $request)
    {
        $url          = static::parseUrl($request->getUrl());
        $this->filter = static::parseFilter($request->getUrl());

        /** @var Page $page */
        $page = Page::find()->where(['url' => $url])->one();

        if (empty($page))
            throw new NotFoundHttpException(Yii::t('app', 'Page not found.'));

        return [
            $page->controller . '/' . 'index',
            [
                'page'   => $page,
                'filter' => $this->filter,
            ]
        ];
    }

    public static function parseUrl($url)
    {
        if (false !== strpos($url, '/filter:')) {
            $url    = substr($url, 0, strpos($url, '/filter:'));
        }

        return $url;
    }

    protected static function parseFilter($url)
    {
        if (false !== ($isFilter = strpos($url, '/filter:'))) {
           $filter      = str_replace('/filter:', '', substr($url, $isFilter));
           $filter      = explode(';', $filter);
           $filterParts = [];

           foreach ($filter as $item) {
               list($name, $value) = explode('=', $item);
               $filterParts[ $name ] = $value;
           }

           return $filterParts;
        }
        return null;
    }

    protected function createCategoryUrl($params)
    {
        if (array_key_exists('page', $params)) {
            $url = static::parseUrl(Yii::$app->request->getUrl());
            if ($params['page'] == 1) {
                return $url;
            } else {
                return $url . '/filter:page=' . $params['page'];
            }
        }
        return '';
    }
}