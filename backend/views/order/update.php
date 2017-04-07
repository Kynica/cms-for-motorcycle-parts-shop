<?php

use yii\helpers\Html;

/* @var $this  yii\web\View */
/* @var $model backend\models\Order */

$this->title = Yii::t('supplier', 'Update {modelClass}: ', [
    'modelClass' => 'Cart',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('supplier', 'Carts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('supplier', 'Update');
?>
<div class="cart-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
