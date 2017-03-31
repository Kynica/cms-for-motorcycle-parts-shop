<?php

use yii\helpers\Url;
use kartik\file\FileInput;

/**
 * @var $this  yii\web\View
 * @var $model common\models\Supplier
 */
?>

<div class="price">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
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
