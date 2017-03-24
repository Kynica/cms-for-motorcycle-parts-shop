<?php

/**
 * @var $this yii\web\View
 * @var $model common\models\Product
 */

$this->title = Yii::t('product', 'Update Product:') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('product', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="product-update">

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>
