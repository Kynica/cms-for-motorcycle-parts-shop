<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Cart AS C;

class Cart extends Widget
{
    public function getViewPath()
    {
        return Yii::$app->view->theme->basePath . '/cart';
    }

    public function run()
    {
        $cartKey      = Yii::$app->request->cookies->getValue('cart');
        $cart         = null;

        if (! empty($cartKey)) {
            /** @var C $cart */
            $cart = C::find()->where(['key' => $cartKey])->one();
        }

        return $this->render('widget', [
            'cart' => $cart,
        ]);
    }
}