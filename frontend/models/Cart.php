<?php

namespace frontend\models;

use Yii;
use yii\base\Exception;
use common\models\Cart AS C;
use common\models\CartProduct;
use yii\web\Cookie;

class Cart extends C
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
            ->viaTable(CartProduct::tableName(), ['cart_id' => 'id']);
    }

    public function addProduct($productId)
    {
        /** @var Product $product */
        $product = Product::find()->where(['id' => $productId])->one();

        if (empty($product))
            throw new Exception('Product not exist');

        /** @var CartProduct $cartProduct */
        $cartProduct = CartProduct::find()
            ->where([
                'cart_id'    => $this->id,
                'product_id' => $product->id
            ])->one();

        if (empty($cartProduct)) {
            $cartProduct = new CartProduct([
                'cart_id'    => $this->id,
                'product_id' => $product->id,
            ]);
        } else {
            $cartProduct->quantity += 1;
        }

        if (! $cartProduct->save())
            throw new Exception('Can\'t save cart product');

        return;
    }

    public static function getByKey($cartKey)
    {
        if (! empty($cartKey)) {
            return static::find()->where(['key' => $cartKey])->one();
        }

        return null;
    }

    public static function create()
    {
        $cart = new static([
            'key' => Yii::$app->security->generateRandomString()
        ]);

        if (! $cart->save())
            throw new Exception('Can\'t save new cart');

        Yii::$app->response->cookies->add(new Cookie([
            'name'  => 'cart',
            'value' => $cart->key,
        ]));

        return $cart;
    }

    public function deleteIfEmpty()
    {
        if (count($this->products) == 0) {
            $this->delete();
            Yii::$app->response->cookies->remove('cart');
        }

        return;
    }

    public function getTotalProduct()
    {
        $total        = 0;
        /** @var CartProduct[] $cartProducts */
        $cartProducts = CartProduct::find()
            ->where(['cart_id' => $this->id])
            ->indexBy('product_id')
            ->all();

        foreach ($cartProducts as $cartProduct) {
            $total += $cartProduct->quantity;
        }

        return $total;
    }

    public function getTotalAmount()
    {
        $amount      = 0;
        /** @var CartProduct[] $cartProduct */
        $cartProduct = CartProduct::find()
            ->where(['cart_id' => $this->id])
            ->indexBy('product_id')
            ->all();

        foreach ($this->products as $product) {
            if (isset($cartProduct[ $product->id ])) {
                $amount += (float) ($product->price * $cartProduct[ $product->id ]->quantity);
            }
        }

        return $amount;
    }

    public function getProductQuantity(Product $product)
    {
        if (isset($this->cartProductsData[ $product->id ])) {
            return $this->cartProductsData[ $product->id ]->quantity;
        }

        return 0;
    }

    public function getProductAmount(Product $product)
    {
        if (isset($this->cartProductsData[ $product->id ])) {
            return (float) ($product->price * $this->cartProductsData[ $product->id ]->quantity);
        }

        return 0;
    }
}