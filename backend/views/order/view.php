<?php

use yii\helpers\Html;
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

        </div>

        <div class="col-md-8">

        </div>
    </div>

</div>
