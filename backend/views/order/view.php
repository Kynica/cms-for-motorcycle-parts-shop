<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this  yii\web\View */
/* @var $model backend\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cart', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-view">

    <div class="row">
        <div class="col-md-4">
            <h3>Покупатель</h3>
            <?= DetailView::widget([
                'model' => $model->customer,
                'attributes' => [
                    'first_name',
                    [
                        'label' => 'Phone number',
                        'value' => preg_replace("/([+]{1})([0-9]{3})([0-9]{2})([0-9]{3})([0-9]{2})([0-9]{2})/", "$1$2 ($3) $4-$5-$6", $model->customer->phone_number),
                    ],
                ]
            ]) ?>

            <h3>Заказ</h3>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Total products',
                        'value' => $model->getTotalProduct(),
                    ],
                    [
                        'label' => 'Total amount',
                        'value' => $model->getTotalAmount(),
                    ],
                ]
            ]) ?>

            <h3>Действия</h3>
            <?php if (null == $model->seller_id): ?>
                <?= Html::a(
                    'Обработать заказ',
                    Url::to([
                        'order/status',
                        'orderId'  => $model->id,
                        'sellerId' => Yii::$app->user->getId(),
                        'statusId' => 1,
                    ]),
                    [
                        'class' => 'btn btn-primary'
                    ]
                ) ?>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <h3>Товары в заказе</h3>
            <div class="row">
            <?php foreach ($model->products as $product): ?>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="thumbnail">
                                <?= Html::img($product->getMainImage()->thumbnail(200, 200, 100, 'order-products')) ?>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= Html::a($product->name, Url::to(['product/update', 'id' => $product->id])) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= $product->price ?>
                                </div>
                                <div class="col-md-1">
                                    x
                                </div>
                                <div class="col-md-1">
                                    <?= $model->getProductQuantity($product) ?>
                                </div>
                                <div class="col-md-1">
                                    =
                                </div>
                                <div class="col-md-3">
                                    <?= $model->getProductAmount($product) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>
