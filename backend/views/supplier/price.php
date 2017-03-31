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
                    'initialPreview' => [],
                    'initialPreviewAsData'=>false,
                    'initialCaption'=>"",
                    'initialPreviewConfig' => [],
                    'uploadUrl' => Url::to(['/supplier/upload-price']),
                    'uploadExtraData' => [
                        'supplier_id'  => $model->id,
                    ],
                    'maxFileCount' => 1
                ]
            ]) ?>
        </div>
    </div>
</div>
