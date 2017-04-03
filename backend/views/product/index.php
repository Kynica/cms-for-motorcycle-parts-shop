<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Product;
use common\models\Currency;
use common\models\Category;
use common\models\ProductImage;

/**
 * @var $this         yii\web\View
 * @var $searchModel  backend\models\ProductSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

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
                'attribute' => 'category_id',
                'content'   => function ($model) {
                    /** @var common\models\Product $model */
                    return $model->getCategoryName();
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'category_id',
                    'data' => Category::getTreeForSelect(),
                    'options' => [
                        'placeholder' => 'Select a category ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
            ],
            [
                'attribute' => 'stock',
                'content'   => function ($model) {
                    /** @var common\models\Product $model */
                    return Product::getStockVariation($model->stock);
                },
                'filter'    => Product::getStockVariation(),
            ],
            [
                'attribute' => 'image',
                'content'   => function ($model) {
                    /** @var $model Product */
                    if (count($model->images)) {
                        return Html::img($model->getMainImage()->thumbnail(120, 120, 100, 'product-index'));
                    }
                    return null;
                }
            ],
            'name',
            [
                'attribute' => 'currency_id',
                'content'   => function ($model) {
                    /** @var common\models\Product $model */
                    if (! empty($model->currency->code))
                        return $model->currency->code;
                    return null;
                },
                'filter'    => ArrayHelper::map(Currency::find()->all(), 'id', 'code')
            ],
            'purchase_price',
            'sell_price',
            'old_price',

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
