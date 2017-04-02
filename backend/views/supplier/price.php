<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use common\models\Category;
use common\models\Currency;

/**
 * @var $this  yii\web\View
 * @var $model common\models\Supplier
 */
?>

<div class="price">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="form-group">
                <?= FileInput::widget([
                    'name' => 'File[upload]',
                    'options'=>[
                        'multiple'=>false
                    ],
                    'pluginOptions' => [
                        'uploadAsync' => false,
                        'initialPreview' => empty($model->getPriceUrl()) ? '' : $model->getPriceUrl(),
                        'initialPreviewAsData'=>true,
                        'initialCaption'=>"",
                        'initialPreviewConfig' => $model->getPriceData(),
                        'uploadUrl' => Url::to(['/supplier/price', 'id' => $model->id]),
                        'uploadExtraData' => [
                            'supplier_id'  => $model->id,
                        ],
                        'maxFileCount' => 1
                    ]
                ]) ?>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-8 col-md-offset-2">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['/supplier/processing-price']
            ]) ?>

            <?= Html::input('hidden', 'supplierId', $model->id) ?>

            <div class="row form-group">
                <div class="col-md-3">
                    <?= Select2::widget([
                        'name' => 'categoryId',
                        'data' => Category::getTreeForSelect(),
                        'options' => [
                            'placeholder' => 'Select a category ...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>

                <div class="col-md-3">
                    <?= Select2::widget([
                        'name' => 'currencyId',
                        'data' => ArrayHelper::map(Currency::find()->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => 'Select a currency ...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>
            </div>

            <?= Html::submitButton(Yii::t('supplier', 'Processing price'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['/supplier/processing-product-image']
            ]) ?>

            <?= Html::input('hidden', 'supplierId', $model->id) ?>

            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Product number</label>
                        <?= Html::input('text', 'productNumber', '', [
                            'class' => 'form-control'
                        ]) ?>
                    </div>
                </div>
            </div>

            <?= Html::submitButton(Yii::t('supplier', 'Processing product image'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
