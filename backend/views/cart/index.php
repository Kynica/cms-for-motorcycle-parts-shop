<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Cart;

/**
 * @var $this         yii\web\View
 * @var $searchModel  backend\models\CartSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('cart', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'Order status',
                'content'   => function ($model) {
                    /** @var $model Cart */
                    return $model->getOrderStatusName();
                }
            ],
            [
                'attribute' => 'Seller',
                'content'   => function ($model) {
                    /** @var $model Cart */
                    return $model->getSellerName();
                }
            ],
            'ordered_at:date',

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ],
        ],
    ]); ?>
</div>
