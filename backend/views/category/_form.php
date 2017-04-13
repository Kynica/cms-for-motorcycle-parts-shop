<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\Category;

/**
 * @var $this yii\web\View
 * @var $model common\models\Category
 * @var $form yii\widgets\ActiveForm
 */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'parent_id')->widget(
                Select2::className(),
                [
                    'data' => Category::getTreeForSelect($model->id),
                    'options' => [
                        'placeholder' => 'Select a category ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]
            )->label(Yii::t('category', 'Parent Category')) ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php if (! $model->isNewRecord): ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_description')->textarea(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'product_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'product_description')->textarea(['maxlength' => true]) ?>

            <?= $form->field($model, 'product_keywords')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'seo_text')->textarea(['maxlength' => true]) ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
