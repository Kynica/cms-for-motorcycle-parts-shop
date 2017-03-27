<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-9">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'stock')->dropDownList($model->getStockVariation()) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'old_price')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'purchase_price')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
