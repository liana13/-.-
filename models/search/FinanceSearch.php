<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use app\models\Finance;
use app\models\Object;
use app\models\User;
use app\models\Tarif;

/**
 * FinanceSearch represents the model behind the search form of `app\models\Finance`.
 */
class FinanceSearch extends Finance
{
    /**
     * @inheritdoc
     */
    public $active_online;
    public $login;

    public function rules()
    {
        return [
            [['id', 'user_id', 'status'], 'integer'],
            [['object_id', 'created_at', 'updated_at', 'tarif_id', 'active_online', 'login', 'price'], 'safe'],
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
        $query = Finance::find();

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
            'user_id' => $this->user_id,
            'tarif_id' => $this->tarif_id,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ]);
        if (!empty($this->object_id) && empty($this->active_online)) {
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
        if (empty($this->object_id) && !empty($this->active_online)) {
            $objid = [];
            $objects = Object::find()->andFilterWhere(['like','act_oplata', $this->active_online])->all();
            foreach ($objects as $object) {
                $objid []= $object->id;
            }
            if (count($objid)==0) {
                $objid = ['0'];
            }
            $query->andFilterWhere(['in','object_id', $objid]);
        }
        if (!empty($this->object_id) && !empty($this->active_online)) {
            $objid = [];
            $objects = Object::find()->andFilterWhere(['like','act_oplata', $this->active_online])->all();
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

        $query->andFilterWhere(['like','price', $this->price]);

        return $dataProvider;
    }
}
