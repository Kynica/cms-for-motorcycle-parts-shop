<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use common\models\SupplierProduct;
use common\models\Product;

class McgController extends Controller
{
    public function actionHello()
    {
        $this->stdout('Hello' . PHP_EOL, Console::FG_GREEN);
    }

    public function actionUpdatePrice()
    {
        try {
            $apiKey = '4dmyyvaulo9dxhl8nl66';
            $url    = 'http://personal.cab/api/v1/?key=' .
                $apiKey .
                '&type=json&method=get_products&products_sku=';

            $supplierProductsQuery = SupplierProduct::find()
                ->where(['supplier_id' => 1])
                ->with(['product.category'])
                ->indexBy('sku')
                ->orderBy(['id' => SORT_ASC]);

            $part = 0;
            /** @var SupplierProduct[] $supplierProducts */
            foreach ($supplierProductsQuery->batch(1000) as $supplierProducts) {
                $skuString = '';
                foreach ($supplierProducts as $supplierProduct) {
                    $skuString !== '' ? $skuString .= '|' : null;
                    $skuString .= $supplierProduct['sku'];
                }

                $content = file_get_contents($url . $skuString);
                $content = json_decode($content);

                foreach ($content->products as $product) {
                    if (! empty($supplierProducts[ $product->sku ])) {
                        /** @var Product $updatedProduct */
                        $updatedProduct                 = $supplierProducts[ $product->sku ]->product;
                        $updatedProduct->purchase_price = $product->price;

                        if (0 === $product->stock) {
                            $updatedProduct->stock = Product::STOCK_OUT;
                        } else {
                            $updatedProduct->stock = Product::STOCK_IN;
                        }

                        $updatedProduct->save();
                    } else {
                        throw new \Exception('supplier product not exist');
                    }
                }
                $part += 1;
                $this->stdout('Part ' . $part . ' is done.' . PHP_EOL, Console::FG_GREEN);
            }

            $this->stdout('All done' . PHP_EOL, Console::FG_GREEN);
        } catch (\Exception $e) {
            $this->stderr(
                'Error -> ' .
                $e->getMessage() .
                ' on ' .
                $e->getFile() .
                ' ' .
                $e->getLine(), Console::FG_RED);
        }
    }
}