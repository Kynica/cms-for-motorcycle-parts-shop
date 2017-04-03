<?php

namespace frontend\models;

use common\models\Product AS P;

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
        return 0;
    }

    public function getOldPrice()
    {
        return $this->old_price;
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
}