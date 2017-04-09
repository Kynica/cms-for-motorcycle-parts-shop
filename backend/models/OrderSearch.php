<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\OrderStatus;

/**
 * CartSearch represents the model behind the search form about `common\models\Cart`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'ordered_at', 'customer_id', 'order_status_id', 'seller_id'], 'integer'],
            [['key', 'is_ordered'], 'safe'],
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
        $orderStatuses = implode(',', ArrayHelper::map(OrderStatus::find()->all(), 'id', 'id'));

        $query = Order::find()
            ->where([
            'is_ordered' => static::IS_ORDERED_YES
            ])->orderBy([new Expression("FIELD(cart.order_status_id, null, {$orderStatuses})")]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'ordered_at'      => $this->ordered_at,
            'customer_id'     => $this->customer_id,
            'order_status_id' => $this->order_status_id,
            'seller_id'       => $this->seller_id,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'is_ordered', $this->is_ordered]);

        return $dataProvider;
    }
}
