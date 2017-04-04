<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap\Nav;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

class Menu extends Nav
{
    public function run()
    {
        return $this->renderItems();
    }

    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if (empty($items)) {
            $items = '';
        } else {
            $linkOptions['data-toggle'] = 'dropdown';
            Html::addCssClass($options, ['widget' => 'dropdown']);
            Html::addCssClass($linkOptions, ['widget' => 'dropdown-toggle']);
            if ($this->dropDownCaret !== '') {
                $label .= ' ' . $this->dropDownCaret;
            }
            if (is_array($items)) {
                if ($this->activateItems) {
                    $items = $this->isChildActive($items, $active);
                }
                $items = $this->renderDropdown($items, $item);
            }
        }

        if ($this->activateItems && $active) {
            Html::addCssClass($linkOptions, 'active');
        }

        if (! $active) {
            return Html::tag(
                'li',
                Html::a($label, $url, $linkOptions) . $items . Html::tag('div', '', ['class' => 'sub']),
                $options
            );
        } else {
            return Html::tag(
                'li',
                Html::tag('div', $label, $linkOptions) . $items . Html::tag('div', '', ['class' => 'sub']),
                $options
            );
        }
    }

    public function isItemActive($item)
    {
        if (Yii::$app->request->getUrl() == $item['url'])
            return true;
        return false;
    }
}