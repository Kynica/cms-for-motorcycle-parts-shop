<?php

namespace frontend\models;

use Yii;
use yii\web\Cookie;
use yii\base\Exception;
use common\models\Cart AS C;
use common\models\CartProduct;
use common\models\Customer;

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

    /**
     * @param  string $cartKey
     * @return array|null|Cart
     */
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

    public function turnIntoOrder(Customer $customer)
    {
        $this->is_ordered      = static::IS_ORDERED_YES;
        $this->ordered_at      = time();
        $this->customer_id     = $customer->id;
        $this->order_status_id = 1; // TODO Little chit. Take new order status id.

        return $this->save();
    }

    public function deleteIfEmpty()
    {
        if (count($this->products) == 0) {
            $this->delete();
            Yii::$app->response->cookies->remove('cart');
        }

        return;
    }
}