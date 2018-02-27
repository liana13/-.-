<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Review;
use app\models\User;
use app\models\Object;

/**
 * ReviewSearch represents the model behind the search form of `app\models\Review`.
 */
class ReviewSearch extends Review
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rate', 'status'], 'integer'],
            [['description', 'created_at', 'updated_at','object_id', 'user_id',], 'safe'],
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
        $query = Review::find();

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
            'rate' => $this->rate,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if (!empty($this->object_id)) {
            $objid = [];
            $objects = Object::find()->all();
            foreach ($objects as $object) {
                if (mb_stripos($object->title, $this->object_id) !== false) {
                    $objid []= $object->id;
                }
            }
            if (count($objid)==0) {
                $objid = ['0'];
            }
            $query->andFilterWhere(['in','object_id', $objid]);
        }
        if (!empty($this->user_id)) {
            $uid = [];
            $users = User::find()->all();
            foreach ($users as $user) {
                if (mb_stripos($user->username, $this->user_id) !== false) {
                    $uid []= $user->id;
                }
            }
            if (count($uid)==0) {
                $uid = ['0'];
            }
            $query->andFilterWhere(['in','user_id', $uid]);
        }

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
