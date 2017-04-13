<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Category;
use common\models\CategoryProductMargin AS CPM;

/**
 * @var $this         yii\web\View
 * @var $searchModel  backend\models\CategorySearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('category', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('category', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => Yii::t('category', 'Parents'),
                'content'   => function ($model) {
                    /** @var $model \common\models\Category */
                    return $model->getParentsNames();
                }
            ],
            'name',
            [
                'attribute' => Yii::t('category', 'Total Products'),
                'content'   => function ($model) {
                    /** @var $model Category */
                    return $model->getTotalProducts();
                }
            ],
            [
                'attribute' => Yii::t('category', 'Margin'),
                'content'   => function ($model) {
                    /** @var $model Category */
                    $content = '';

                    foreach ($model->productMargin as $margin) {
                        $marginSymbol = $margin->margin_type == CPM::MARGIN_TYPE_PERCENT ?
                            '%' : $margin->currency->symbol;
                        $content .= Html::tag(
                            'div',
                            $margin->currency->code . ' + ' . $margin->margin . ' ' . $marginSymbol
                        );
                    }

                    return $content;
                }
            ],
            [
                'attribute' => Yii::t('category', 'url'),
                'content'   => function ($model) {
                    /** @var $model Category */
                    return $model->page->url;
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
