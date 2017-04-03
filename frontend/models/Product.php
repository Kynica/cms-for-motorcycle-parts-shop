<?php

namespace frontend\models;

use common\models\Product AS P;
use common\models\ProductImage;

class Product extends P
{
    const PRICE_NOT_SET = '0.00';

    public function isDiscountSet()
    {
        $discount = false;

        if (static::PRICE_NOT_SET != $this->old_price && $this->old_price > $this->sell_price) {
            $discount = true;
        }

        return $discount;
    }

    public function getDiscount()
    {
        return ($this->old_price * $this->currency->rate) - $this->price;
    }

    public function getOldPrice()
    {
        return $this->old_price * $this->currency->rate;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->page->url;
    }

    /**
     * @return ProductImage[]
     */
    public function getImagesWithoutMain()
    {
        $images = [];

        foreach ($this->images as $sort => $image) {
            if (1 != $sort)
                $images[] = $image;
        }

        return $images;
    }

    public function getBreadcrumbData()
    {
        $breadcrumb = [];

        if (! empty($this->category)) {
            /** @var Category[] $categoryParents */
            $categoryParents =  $this->category->getParents();

            foreach ($categoryParents as $categoryParent) {
                $breadcrumb[] = [
                    'label' => $categoryParent->name,
                    'url'   => $categoryParent->page->url
                ];
            }

            $breadcrumb[] = [
                'label' => $this->category->name,
                'url'   => $this->category->page->url
            ];

            $breadcrumb[] = $this->name;
        }

        return $breadcrumb;
    }
}