<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bookings;

/**
 * BookingsSearch represents the model behind the search form about `app\models\Bookings`.
 */
class BookingsSearch extends Bookings
{

	public $balbolo;
	public $myuser;
	public $username;
	public $future;
	public $past;
	public $owner;
	public $balboloname;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'balbolo_id', 'user_id', 'unit_points', 'confirm_status', 'cancel_status', 'guest', 'unit', 'total_point'], 'integer'],
            [['balbolo_name', 'balbolo', 'myuser', 'username', 'comission', 'cancel_status', 'balboloname'], 'safe'],
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
        $query = Bookings::find()->joinWith(['balbolo','user']);
		
		if($this->myuser){
			$query->join('INNER JOIN','posts as p','p.id = bookings.balbolo_id')->join('INNER JOIN','user as owner','owner.id = p.user_id');
		}

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		$dataProvider->sort->attributes['balbolo'] = [
            'asc' => ['posts.name' => SORT_ASC],
            'desc' => ['posts.name' => SORT_DESC]
        ];
		
		$dataProvider->sort->attributes['balboloname'] = [
            'asc' => ['posts.name' => SORT_ASC],
            'desc' => ['posts.name' => SORT_DESC]
        ];
		
		$dataProvider->sort->attributes['username'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'balbolo_id' => $this->balbolo_id,
            //'owner.id' => $this->myuser,
            'unit_points' => $this->unit_points,
            'confirm_status' => $this->confirm_status,
            //'cancel_status' => $this->confirm_status,
            'guest' => $this->guest,
            'unit' => $this->unit,
            'total_point' => $this->total_point,
            'start_date' => $this->start_date,
            //'bookings.created_at' => $this->created_at,
        ]);
		
		//$query->andFilterWhere(['like', 'bookings.created_at', $this->created_at]);
		$query->andFilterWhere(['like', 'balbolo_name', $this->balbolo_name]);
		$query->andFilterWhere(['like', 'cancel_status', $this->cancel_status]);
		$query->andFilterWhere(['like', 'posts.name', $this->balbolo]);
		$query->andFilterWhere(['like', 'posts.name', $this->balboloname]);
		
		if($this->username){
			$query->andFilterWhere(['like', 'user.username', $this->username]);
		}
		$query->andFilterWhere(['bookings.user_id' => $this->user_id]);
		
		if($this->myuser){
			$query->andFilterWhere(['owner.id' => $this->myuser]);
		}
		
		if($this->past){
			$query->andFilterWhere(['<','start_date',$this->past]);
		}
		
		if($this->future){
			$query->andFilterWhere(['>=','start_date',$this->future]);
		}

        return $dataProvider;
    }
}
