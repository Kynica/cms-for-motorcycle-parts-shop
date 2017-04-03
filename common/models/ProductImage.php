<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Inflector;
use common\components\ImageCache;

/**
 * This is the model class for table "product_image".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string  $path
 * @property integer $sort
 *
 * @property Product $product
 */
class ProductImage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'path', 'sort'], 'required'],
            [['product_id', 'sort'], 'integer'],
            [['path'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('product-image', 'ID'),
            'product_id' => Yii::t('product-image', 'Product ID'),
            'path'       => Yii::t('product-image', 'Path'),
            'sort'       => Yii::t('product-image', 'Sort'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public static function getStorageFolder()
    {
        return '/' . Yii::$app->params['uploadDir'] . '/' . 'product-image' . '/' . date("Y-m") . '/' . date("d");
    }

    public static function getImageName(Product $product)
    {
        $imageNumber = static::find()->where(['product_id' => $product->id])->count();
        $imageNumber += 1;
        return Inflector::slug($product->name . '-' . $imageNumber . substr(time(), -2)) . '.jpg';
    }

    public static function uploadFor(Product $product)
    {
        $imageNumber = static::find()->where(['product_id' => $product->id])->count();
        $newImages   = File::uploadImages(static::getStorageFolder(), $product->name);

        foreach ($newImages as $name) {
            $imageNumber += 1;
            $new = new static([
                'product_id' => $product->id,
                'path'       => static::getStorageFolder() . '/' . $name,
                'sort'       => $imageNumber
            ]);

            if (! $new->save())
                throw new Exception('Can\'t save new image to database');
        }

        return;
    }

    public static function addFor(Product $product, $image)
    {
        $imageNumber = static::find()->where(['product_id' => $product->id])->count();
        $imageNumber += 1;

        $new = new static([
            'product_id' => $product->id,
            'path'       => static::getStorageFolder() . '/' .$image,
            'sort'       => $imageNumber
        ]);

        if (! $new->save())
            throw new Exception('Can\'t save image');

        return true;
    }

    public static function deleteOne($id)
    {
        $image = static::findOne($id);
        $product = Product::findOne($image->product_id);
        if (! empty($product) && $image->delete()) {
            File::delete($image->path);
            Yii::$app->db->createCommand()
                ->update(
                    static::tableName(),
                    ['sort' => new Expression('sort - 1')],
                    'product_id = ' . $image->product_id . ' AND sort > ' . $image->sort
                )->execute();
        }
        return;
    }

    public static function deleteAllFor(Product $product)
    {
        /** @var static[] $images */
        $images = static::find()->where(['product_id' => $product->id])->all();
        foreach ($images as $image) {
            if (! $image->delete())
                throw new Exception('Can\'t delete image from database where product id is' . $product->id);

            if (File::delete($image->path))
                throw new Exception('Can\'t delete product image where product id is ' . $product->id);
        }
        return;
    }

    public function getFromCache($width = 200, $height = 200, $quality = 100, $scope = 'global')
    {
        return ImageCache::create(
            $this->path,
            $width, $height, $quality, $scope
        );
    }
}
