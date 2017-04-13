<?php

namespace backend\components;

use yii\helpers\FileHelper;
use common\models\Supplier;
use common\models\SupplierProduct;
use common\models\Product;
use common\models\ProductImage;
use common\models\File;

class SupplierImageGrabber
{
    public static function motocenter(Supplier $supplier, $productNumber)
    {
        /** @var SupplierProduct[] $supplierProducts */
        $supplierProducts = SupplierProduct::find()
            ->where([
                'supplier_id'      => $supplier->id,
                'image_downloaded' => SupplierProduct::IMAGE_DOWNLOADED_NO
            ])
            ->limit($productNumber)
            ->all();

        $num = 0;
        foreach ($supplierProducts as $supplierProduct) {
            $imageUrl = $supplier->site . 'products/large/'. $supplierProduct->sku .'.jpg';

            if (Grabber::isPageExist($imageUrl)) {
                $product   = Product::findOne($supplierProduct->product_id);
                $imagePath = File::getFullPath(ProductImage::getStorageFolder(), '@frontend/web');
                $imageName = ProductImage::getImageName($product);
                $savePath  = $imagePath . '/' . $imageName;

                FileHelper::createDirectory($imagePath);

                if (Grabber::getImageAndSave($imageUrl, $savePath)) {
                    if (ProductImage::addFor($product, $imageName)) {
                        $supplierProduct->image_downloaded = SupplierProduct::IMAGE_DOWNLOADED_YES;
                        $supplierProduct->save();
                    }
                }
            }

            echo ++$num . ', ';
            flush();
        }
    }
}