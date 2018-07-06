<?php

namespace app\models;

use Yii;
use frontend\models\Posts2;
use app\models\Ratings;
use app\models\UserRatings;
use common\models\User;

/**
 * This is the model class for table "bookings".
 *
 * @property integer $id
 * @property integer $balbolo_id
 * @property integer $user_id
 * @property integer $unit_points
 * @property integer $confirm_status
 * @property integer $guest
 * @property integer $unit
 * @property integer $total_point
 * @property integer $start_date
 * @property string $created_at
 */
class Bookings extends \yii\db\ActiveRecord
{
	public $rejection;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bookings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balbolo_id', 'user_id', 'unit_points', 'additional', 'additional_after', 'confirm_status', 'guest', 'unit', 'total_point', 'start_date', 'comission'], 'required'],
            [['balbolo_id', 'user_id', 'unit_points', 'confirm_status', 'cancel_status', 'guest', 'unit', 'total_point'], 'integer'],
			[['cancel_status'], 'default', 'value'=> '0'],
            [['balbolo_name', 'unit_name', 'created_at', 'comission', 'cancel_status'], 'safe'],
            [['start_date'], 'date','format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'balbolo_id' => 'Balbolo ID',
            'balbolo_name' => 'Balbolo Name',
            'user_id' => 'User ID',
            'comission' => 'Admin Comission',
            'unit_points' => 'Unit Points',
            'confirm_status' => 'Status',
            'guest' => 'Guest(s)',
            'unit' => 'Unit(s)',
            'total_point' => 'Total Point',
            'start_date' => 'Start Date',
            'created_at' => 'Date',
        ];
    }
	
	public function getBalbolo()
    {
        return $this->hasOne(Posts2::className(), ['id' => 'balbolo_id']);
    }
	
	public function getBalboloname(){
		$balbolo = $this->balbolo;
        return $balbolo ? $balbolo->name : '';
	}
	
	public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
	
	public function getUsername(){
		$user = $this->user;
        return $user ? $user->username : '';
	}
	
	public function getRating()
    {
        return $this->hasOne(Ratings::className(), ['booking_id' => 'id']);
    }
	
	public function getUserrating()
    {
        return $this->hasOne(UserRatings::className(), ['booking_id' => 'id']);
    }
	
	
	/* public function getMybooking(){
		return $this->hasMany(Posts2::className(), ['id' => 'balbolo_id'])->where(['posts.user_id' => Yii::$aap->user->id]);
            //->viaTable('user', ['id' => 'posts.user_id'])
           // ->viaTable('mysecondTable', ['id_2' => 'id_2']);
	} */
	
}
