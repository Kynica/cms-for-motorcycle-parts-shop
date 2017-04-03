<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Page;
use frontend\models\Product;

class ProductController extends Controller
{
    public function actionIndex(Page $page)
    {
        $product = Product::find()->where(['id' => $page->entity_id])->one();

        return $this->render('index', [
            'product' => $product,
        ]);
    }
}