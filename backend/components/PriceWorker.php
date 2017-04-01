<?php

namespace backend\components;

use Yii;
use yii\base\Exception;
use common\models\Supplier;
use common\models\File;
use common\models\Product;
use common\models\SupplierProduct;
use common\models\Category;
use common\models\Currency;

class PriceWorker
{
    public static function processingPrice (Supplier $supplier, Category $category = null, Currency $currency = null)
    {
        set_time_limit(0);

        $path  = File::getFullPath($supplier->getPriceLink());
        /** @var array $price */
        $price = file($path);

        $template = explode('|', str_replace("\n", '', array_shift($price)));

        $db = Yii::$app->db;

        foreach ($price as $item) {
            $item     = str_replace("\n", '', $item);
            $elements = explode('|', $item);

            $transaction = $db->beginTransaction();

            try {
                $date = static::parse($supplier, $template, $elements, $category, $currency);

                $transaction->commit();
            } catch (Exception $e) {
                $date = $e;
                $transaction->rollBack();
            }

            echo var_dump($date);
            flush();
        }

        return '';
    }

    protected static function parse (
        Supplier $supplier,
        array $template,
        array $elements,
        Category $category = null,
        Currency $currency = null
    ) {
        if (count($elements) == 0)
            throw new Exception('Elements array is empty');

        /** @var Product $tmpProduct */
        $newProduct         = new Product();
        $newSupplierProduct = new SupplierProduct([
            'supplier_id' => $supplier->id
        ]);

        foreach ($elements as $key => $value) {
            if (! isset($template[ $key ]))
                throw new Exception('Template key not exist.');

            switch ($template[ $key ]) {
                case '{sku}':
                    $newSupplierProduct->sku = $value;
                    break;
                case '{url}':
                    $newSupplierProduct->url = $value;
                    break;
                case '{name}':
                    $newProduct->name = preg_replace('/\s+/', ' ', $value);
                    break;
                case '{price}':
                    $newProduct->price = str_replace(',', '.', $value);
                    break;
                case '{purchasePrice}':
                    $newProduct->purchase_price = str_replace(',', '.', $value);
                    break;
                case '{stock}':
                    $newProduct->stock = $value == '+' ? Product::STOCK_IN : Product::STOCK_OUT;
                    break;
                default:
                    throw new Exception('Unknown template selector - ' . $template[ $key ] . '.');
                    break;
            }
        }

        /** @var SupplierProduct $supplierProduct */
        $supplierProduct = SupplierProduct::find()->where(['sku' => $newSupplierProduct->sku])->one();
        $product         = new Product();

        if (empty($supplierProduct)) {
            if (! empty($category))
                $newProduct->category_id = $category->id;

            if (! empty($currency))
                $newProduct->currency_id = $currency->id;

            if (! $newProduct->validate())
                throw new Exception('Not enough data to save Product. Validation not passed.');

            if (! $newProduct->save())
                throw new Exception('Can\'t save Product.');

            $newSupplierProduct->product_id = $newProduct->id;

            if (! $newSupplierProduct->validate())
                throw new Exception('Not enough data to save SupplierProduct. Validation not passed.');

            if (! $newSupplierProduct->save())
                throw new Exception('Can\'t save SupplierProduct.');

            $supplierProduct = $newSupplierProduct;
        } else {
            $product = Product::findOne($supplierProduct->product_id);

            if (empty($product))
                throw new Exception('Product with id ' . $supplierProduct->product_id . ' not exist');

            $product->purchase_price = $newProduct->purchase_price;

            if (! empty($category))
                $product->category_id = $category->id;

            if (! empty($currency))
                $product->currency_id = $currency->id;

            if (! $product->save())
                throw new Exception('Can\'t save Product.');
        }

        return [
            $product,
            $supplierProduct
        ];
    }
}