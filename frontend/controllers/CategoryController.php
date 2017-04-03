<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use common\models\Page;
use frontend\models\Category;
use frontend\models\Product;

class CategoryController extends Controller
{
    public function actionIndex(Page $page)
    {
        /** @var Category $category */
        $category = Category::find()->where(['id' => $page->entity_id])->one();

        if (empty($category))
            throw new NotFoundHttpException(Yii::t('category', 'Category not found.'));

        $productQuery = Product::find()
            ->where([
                'in',
                'category_id',
                $category->getIDsForProducts()
            ]);

        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount'      => $productQuery->count()
        ]);

        $products = $productQuery
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        return $this->render('index', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}