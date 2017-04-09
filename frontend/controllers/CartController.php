<?php

namespace frontend\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use frontend\models\Cart;
use common\models\Customer;

class CartController extends Controller
{
    public function actionIndex()
    {
        $cartKey  = Yii::$app->request->cookies->getValue('cart');
        $cart     = Cart::getByKey($cartKey);
        $customer = new Customer();

        if (! empty($cartKey) && empty($cart)) {
            Yii::$app->response->cookies->remove('cart');
        }

        if (! empty($cart) && Yii::$app->request->isPost) {
            if ($customer->load(Yii::$app->request->post()) && $customer->validate()) {
                $existCustomer = Customer::find()->where(['phone_number' => $customer->phone_number])->one();

                if (! empty($existCustomer)) {
                    $customer = $existCustomer;
                } else {
                    $customer->save();
                }

                if ($cart->turnIntoOrder($customer)) {
                    $cart = new Cart();
                    Yii::$app->response->cookies->remove('cart');
                }
            }
        }

        return $this->render('index', [
            'cart'     => $cart,
            'customer' => $customer,
        ]);
    }

    public function actionAddProduct()
    {
        $cartKey   = Yii::$app->request->cookies->getValue('cart');
        $productId = (int) Yii::$app->request->post('product');

        if (empty($productId)) {
            $this->redirect(['cart/index'])->send();
            return;
        }

        try {
            $cart = Cart::getByKey($cartKey);

            if (empty($cart))
                $cart = Cart::create();
        } catch (Exception $e) {
            $this->redirect(['cart/index'])->send();
            return;
        }

        try {
            $cart->addProduct($productId);
        } catch (Exception $e) {
            $cart->deleteIfEmpty();
            $this->redirect(['cart/index'])->send();
            return;
        }

        $this->redirect(['cart/index'])->send();
        return;
    }

    public function actionRemoveProduct()
    {

    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }
}