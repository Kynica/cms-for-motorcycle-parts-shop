<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this     yii\web\View
 * @var $model    common\models\Profile
 * @var $form     yii\widgets\ActiveForm
 * @var $userForm backend\models\UserForm
 */
?>

<div class="profile-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userForm, 'username')->textInput(['maxlength' => true])->label('login') ?>

    <?= $form->field($userForm, 'password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userForm, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
