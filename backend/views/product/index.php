<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Product;
use common\models\Currency;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('product', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('product', 'Create Product'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sku',
            [
                'attribute' => 'stock',
                'content'   => function ($model) {
                    /** @var common\models\Product $model */
                    return Product::getStockVariation($model->stock);
                },
                'filter'    => Product::getStockVariation(),
            ],
            'name',
            [
                'attribute' => 'currency_id',
                'content'   => function ($model) {
                    /** @var common\models\Product $model */
                    return $model->currency->code;
                },
                'filter'    => ArrayHelper::map(Currency::find()->all(), 'id', 'code')
            ],
            'purchase_price',
            'price',
            'old_price',

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
