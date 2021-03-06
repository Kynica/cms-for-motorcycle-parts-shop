<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\file\FileInput;
use common\models\Product;
use common\models\Currency;
use common\models\Category;
use common\models\ProductImage;

/**
 * @var $this yii\web\View
 * @var $model common\models\Product
 * @var $form yii\widgets\ActiveForm
 * @var Currency[] $currencyList
 */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'stock')->dropDownList(Product::getStockVariation()) ?>
        </div>
        <div class="col-md-7">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'category_id')->widget(
                Select2::className(),
                [
                    'data' => Category::getTreeForSelect(),
                    'options' => [
                        'placeholder' => 'Select a category ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]
            ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'currency_id')->dropDownList(
                ArrayHelper::map($currencyList, 'id', 'name'),
                [
                    'prompt' => 'Select currency',
                ]
            ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'purchase_price')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'sell_price')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'old_price')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">

        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label><?= $model->getAttributeLabel('price') ?></label>
                <?= Html::input('text', 'price', $model->price, [
                    'class' => 'form-control',
                    'disabled' => true
                ]) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group">
                <?= FileInput::widget([
                    'name' => 'File[upload][]',
                    'options'=>[
                        'multiple'=>true
                    ],
                    'pluginOptions' => [
                        'uploadAsync' => false,
                        'initialPreview' => $model->getImagesForFileInput(1000, 1000, 100, 'product-update-form'),
                        'initialPreviewAsData'=>true,
                        'initialCaption'=>"",
                        'initialPreviewConfig' => $model->getImagesDataForFileInput(),
                        'uploadUrl' => Url::to(['/product/image-upload']),
                        'uploadExtraData' => [
                            'product_id'  => $model->id,
                        ],
                        'maxFileCount' => 20
                    ]
                ]) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
