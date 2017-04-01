<?php

use backend\components\PriceWorker;

/**
 * @var $this     yii\web\View;
 * @var $model    common\models\Supplier
 * @var $category common\models\Category
 * @var $currency common\models\Currency
 */
?>

<div class="processing-price">
    <?php PriceWorker::processingPrice($model, $category, $currency) ?>
</div>
