<?php

namespace frontend\widgets;

use yii\helpers\Html;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use frontend\models\Category;

/**
 * Class CategoryMenu
 * @package frontend\widgets
 *
 */

class CategoryMenu extends Widget
{
    public $label;
    /**
     * @var Category
     */
    public $category;

    public function init()
    {
        parent::init();
        if ($this->category === null) {
            throw new InvalidConfigException('Please specify the "category" property.');
        }
    }

    public function run()
    {
        $prefix            = '';
        $content           = '';
        /** @var Category[] $parents */
        $parents           = $this->category->getParents();
        /** @var Category[] $directDescendants */
        $directDescendants = $this->category->directDescendants;

        if (! empty($this->label)) {
            $content .= Html::tag('div', $this->label, ['class' => 'title']);
        }

        foreach ($parents as $parent) {
            $c = Html::a($prefix . ' ' . $parent->name, $parent->getUrl());
            $content .= Html::tag('div', $c, ['class' => 'parent']);
            $prefix .= '-';
        }

        $content .= Html::tag('div', $prefix . ' ' . $this->category->name, ['class' => 'current']);
        $prefix .= '-';

        foreach ($directDescendants as $descendant) {
            $c = Html::a($prefix . ' ' . $descendant->name, $descendant->getUrl());
            $content .= Html::tag('div', $c, ['class' => 'child']);
        }

        return $content;
    }
}