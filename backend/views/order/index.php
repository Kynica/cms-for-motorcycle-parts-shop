<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Order;

/**
 * @var $this         yii\web\View
 * @var $searchModel  backend\models\OrderSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('order', 'Orders');
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
                'attribute' => Yii::t('order', 'Order Status'),
                'content'   => function ($model) {
                    /** @var $model Order */
                    return $model->getOrderStatusName();
                }
            ],
            [
                'attribute' => Yii::t('order', 'Seller'),
                'content'   => function ($model) {
                    /** @var $model Order */
                    return $model->getSellerName();
                }
            ],
            [
                'attribute' => Yii::t('order', 'Total Products'),
                'content'   => function ($model) {
                    /** @var $model Order */
                    return $model->getTotalProduct();
                }
            ],
            [
                'attribute' => Yii::t('order', 'Total Amount'),
                'content'   => function ($model) {
                    /** @var $model Order */
                    return $model->getTotalAmount() . ' грн.';
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
