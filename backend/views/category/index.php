<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Category;

/**
 * @var $this         yii\web\View
 * @var $searchModel  backend\models\CategorySearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */


$this->title = Yii::t('product', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('product', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'Parents',
                'content'   => function ($model) {
                    /** @var $model \common\models\Category */
                    return $model->getParentsNames();
                }
            ],
            'name',
            [
                'attribute' => 'totalProducts',
                'content'   => function ($model) {
                    /** @var $model Category */
                    return $model->getTotalProducts();
                }
            ],

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{product-margin} {update} {delete}',
                'buttons'  => [
                    'product-margin' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-usd"></span>', $url);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
