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

    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    public function getSeoText()
    {
        return $this->seo_text;
    }
}