<?php

namespace frontend\models;

use common\models\Category AS C;
use yii\helpers\ArrayHelper;

class Category extends C
{
    public function getIDsForProducts()
    {
        $children = ArrayHelper::map($this->children, 'id', 'id');
        array_unshift($children, $this->id);

        return $children;
    }

    public function getName()
    {
        return $this->name;
    }
}