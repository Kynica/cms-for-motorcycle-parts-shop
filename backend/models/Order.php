<?php

namespace backend\models;

use Yii;
use common\models\Cart;

class Order extends Cart
{
    public function isStatusCanByChanged()
    {
        if (empty($this->seller_id)) {
            return true;
        }

        if (! empty($this->seller_id) && Yii::$app->user->getId() == $this->seller_id) {
            return true;
        } else {
            return false;
        }
    }
}