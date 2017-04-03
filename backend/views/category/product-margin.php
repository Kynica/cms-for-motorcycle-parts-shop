<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CategoryProductMargin;

/**
 * @var $this    yii\web\View;
 * @var $margins CategoryProductMargin[]
 */
?>

<div class="product-margin">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <?php $form = ActiveForm::begin() ?>
            <?php foreach ($margins as $margin): ?>

                <div class="row">
                    <div class="col-md-3">
                        <label>Currency code</label>
                        <?= Html::input('text', "[$margin->id]currency", $margin->currency->code, [
                            'class' => 'form-control',
                            'disabled' => true
                        ]) ?>
                    </div>

                    <div class="col-md-3">
                        <?= $form->field($margin, "[{$margin->id}]margin_type")->dropDownList(
                            CategoryProductMargin::getMarginTypes()
                        ) ?>
                    </div>

                    <div class="col-md-3">
                        <?= $form->field($margin, "[{$margin->id}]margin")->textInput() ?>
                    </div>

                    <div class="col-md-3">
                        <label>Products with this currency</label>
                        <?= Html::input(
                            'text',
                            "[$margin->id]totalProduct",
                            $margin->category->getTotalProductsWhereCurrency($margin->currency),
                            [
                                'class' => 'form-control',
                                'disabled' => true
                            ]
                        ) ?>
                    </div>
                </div>

            <?php endforeach; ?>

        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
