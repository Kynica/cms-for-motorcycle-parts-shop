<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use common\models\SupplierProduct;
use common\models\Product;

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

    public function actionSetCurrencyForProduct()
    {
        $productQuery = Product::find()->where(['is', 'currency_id', null]);

        /** @var Product[] $products */
        foreach ($productQuery->batch() as $products) {
            foreach ($products as $product) {
                $product->currency_id = 1;
                $product->save();
            }
        }

        $this->stdout('Done' . PHP_EOL. Console::FG_GREEN);
    }
}