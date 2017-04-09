<?php

use backend\components\SupplierImageGrabber;

/**
 * @var $this yii\web\View
 * @var $model common\models\Supplier
 * @var $productNumber integer
 */
?>

<div class="processing-product-image">
    <?php $method = $model->code; ?>
    <?php SupplierImageGrabber::$method($model, $productNumber); ?>
</div>
