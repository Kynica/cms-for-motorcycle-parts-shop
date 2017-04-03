<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Page;
use common\models\Category;

class CategoryController extends Controller
{
    public function actionIndex(Page $page)
    {
        /** @var Category $category */
        $category = Category::find()->where(['id' => $page->entity_id])->one();

        if (empty($category))
            throw new NotFoundHttpException(Yii::t('category', 'Category not found.'));

        return $this->renderContent($category->name);
    }
}