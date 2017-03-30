<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Expression;
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
    protected static $images = [];
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

    public static function getStorageFolder(Product $product)
    {
        return Yii::$app->params['uploadDir'] . '/' . 'product' . '/' . $product->id;
    }

    public static function uploadFor(Product $product)
    {
        $imageNumber = static::find()->where(['product_id' => $product->id])->count();
        $newImages   = File::uploadImages(static::getStorageFolder($product), $product->name);

        foreach ($newImages as $name) {
            $imageNumber += 1;
            $new = new static([
                'product_id' => $product->id,
                'path'       => $name,
                'sort'       => $imageNumber
            ]);

            if (! $new->save())
                throw new Exception('Can\'t save new image to database');
        }

        return;
    }

    public static function deleteOne($id)
    {
        $image = static::findOne($id);
        if ($image->delete()) {
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

    public static function loadImagesFor(Product $product)
    {
        if (! array_key_exists($product->id, static::$images)) {
            static::$images[ $product->id ] = static::find()
                ->where(['product_id' => $product->id])
                ->orderBy(['sort' => SORT_ASC])
                ->indexBy('sort')
                ->all();
        }
    }

    public static function getImages(Product $product, $width = 200, $height = 200, $quality = 100, $scope = 'global')
    {
        static::loadImagesFor($product);

        $images = [];
        foreach (static::$images[ $product->id ] as $image) {
            $images[] = ImageCache::create(
                ProductImage::getStorageFolder($product) . '/' . $image->path,
                $width, $height, $quality, $scope
            );
        }
        return $images;
    }

    public static function getImagesData(Product $product)
    {
        $images = [];
        foreach (static::$images[ $product->id ] as $image) {
            /** @var $image ProductImage */
            $images[] = [
                'url' => Url::to(['/product/image-delete', 'imageId' => $image->id])
            ];
        }
        return $images;
    }
}
