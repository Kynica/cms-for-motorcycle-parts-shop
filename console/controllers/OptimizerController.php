<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use common\models\SupplierProduct;

class OptimizerController extends Controller
{
    public function actionHello()
    {
        $this->stdout('Hello' . PHP_EOL, Console::FG_GREEN);
    }

    /**
     * Remove supplier products where product_id is null.
     */
    public function actionRemoveEmptySupplierProducts()
    {
        try {
            $supplierProductsQuery = SupplierProduct::find()
                ->where(['product_id' => null]);

            /** @var SupplierProduct[] $supplierProducts */
            foreach ($supplierProductsQuery->batch() as $supplierProducts) {
                foreach ($supplierProducts as $supplierProduct) {
                    $supplierProduct->delete();
                }
            }

            $this->stdout('Done' . PHP_EOL, Console::FG_GREEN);
        } catch (\Exception $e) {
            $this->stderr($e->getMessage() . PHP_EOL, Console::FG_RED);
        }
    }
}