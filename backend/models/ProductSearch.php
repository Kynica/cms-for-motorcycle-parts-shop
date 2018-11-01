<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form about `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'currency_id', 'category_id'], 'integer'],
            [['sku', 'name', 'stock', 'created_at', 'updated_at', 'currency_id'], 'safe'],
            [['price', 'old_price', 'purchase_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find()
            ->with([
                'category',
                'currency'
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'             => $this->id,
            'stock'          => $this->stock,
            'price'          => $this->price,
            'old_price'      => $this->old_price,
            'purchase_price' => $this->purchase_price,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'currency_id'    => $this->currency_id,
            'category_id'    => $this->category_id,
        ]);

        if (! empty($this->name) && $this->name != '') {
            $nameParts = explode(' ', $this->name);

            foreach ($nameParts as $part) {
                $query->andFilterWhere(['like', 'name', $part]);
            }
        }

        $query->andFilterWhere(['like', 'sku', $this->sku]);

        return $dataProvider;
    }
}
