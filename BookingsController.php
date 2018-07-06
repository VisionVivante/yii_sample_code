<?php

namespace frontend\controllers;

use Yii;
use app\models\Bookings;
use app\models\Ratings;
use app\models\RatingBrief;
use app\models\BookingsSearch;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use frontend\models\AdminSettings;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use frontend\models\Profile;

/**
 * BookingsController implements the CRUD actions for Bookings model.
 */
class BookingsController extends Controller
{

	public $layout = 'dashboard';
	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','book-in','book-passed','view','buying','selling','view-order','upcoming'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
					[
                        'actions' => ['decline-booking'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
                           return $this->isUserAuthor(true);
                        }
                    ],
					[
                        'actions' => ['cancel'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
                           return $this->isUserCanCancel(true);
                        }
                    ],
					[
                        'actions' => ['cancel-bookings'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
                           return $this->isUserCanCancel();
                        }
                    ],
					[
                        'actions' => ['accept','decline'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
                           return $this->isUserAuthor();
                        }
                    ],
					[
                        'actions' => ['rate'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
                           return $this->canRate();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Bookings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookingsSearch(['user_id'=>Yii::$app->user->id,'cancel_status' => '0']);
		$searchModel->past = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->where("start_date < '".date('Y-m-d')."'");
		//$dataProvider->query->andWhere("bookings.user_id = '".Yii::$app->user->id."'");

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionSelling()
    {
        $searchModel = new BookingsSearch(['confirm_status'=>'1']);
		$searchModel->myuser = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('selling', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionBuying()
    {
        $searchModel = new BookingsSearch(['user_id'=>Yii::$app->user->id,'confirm_status'=>'1']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('buying', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionUpcoming()
    {
        $searchModel = new BookingsSearch(['user_id'=>Yii::$app->user->id,'cancel_status' => '0']);
		$searchModel->future = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->where("start_date >= '".date('Y-m-d')."'");
		//$dataProvider->query->andWhere("bookings.user_id = '".Yii::$app->user->id."'");

        return $this->render('upcoming', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bookings model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	
	public function actionViewOrder($id)
    {
        return $this->render('vieworder', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bookings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bookings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bookings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Bookings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete__0($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Bookings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bookings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bookings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionBookIn(){
		
		$searchModel = new BookingsSearch(['cancel_status' => '0']);
		$searchModel->myuser = Yii::$app->user->id;
		$searchModel->future = date('Y-m-d');
		$searchModel->owner = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->where("start_date >= '".date('Y-m-d')."'");
		//$dataProvider->query->andwhere("owner.id = '".Yii::$app->user->id."'");

        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
	}
	
	public function actionBookPassed(){
		
		$searchModel = new BookingsSearch(['cancel_status' => '0']);
		$searchModel->myuser = Yii::$app->user->id;
		$searchModel->past = date('Y-m-d');
		$searchModel->owner = Yii::$app->user->id;
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->where("start_date < '".date('Y-m-d')."'");
		//$dataProvider->query->andwhere("owner.id = '".Yii::$app->user->id."'");

        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
	}
	
	public function actionAccept($id)
    {
        $model = $this->findModel($id);
		$model->confirm_status = '1';
		$points=AdminSettings::find()
                        ->where(['id'=>1])
                        ->one();
		
		$id=Yii::$app->user->id;
		$profilepoints=Profile::find()
              ->where(['user_id'=>$id])
              ->one();
			$percent=$model->total_point/$points->admin_commission;//30
		    $value=$model->total_point-$percent;
			$profilepoints->points=$profilepoints->points+$value;
		    $profilepoints->update();
		
		if($model->save()){
			$from = Yii::$app->params['adminEmail'];
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: " . $from;
			
			// seller mail
			if($model->balbolo && $model->balbolo->user && $model->balbolo->user->email){
				$to_seller = $model->balbolo->user->email;
				$subject_seller = 'Your have confirmed your booking';
				$body_seller = 'You have successfully confirmed your balbolo bookinf for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/book-in',true) .'.<br><br>Thanks';
				
				mail($to_seller,$subject_seller,$body_seller,$headers);
			}
			
			//buyer mail
			if($model->user && $model->balbolo){
				$to_Buyer = $model->user->email;
				$sublect_buyer = 'Your Booking has been confirmed';
				$body_buyer = 'Your booking has been confirmed by balbolo seller  for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/index',true) .' .<br><br>Thanks';
				
				mail($to_Buyer,$sublect_buyer,$body_buyer,$headers);
			}
			
			\Yii::$app->session->setFlash('success','Booking Accepted Successfully');
		}else{
			\Yii::$app->session->setFlash('error','Unable to accept booking');
		}
		return $this->redirect(['book-in']);
    }
	
	public function actionDecline($id)
    {
        $model = $this->findModel($id);
		$model->confirm_status = '2';
		if($model->save()){
		
			$from = Yii::$app->params['adminEmail'];
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: " . $from;
			
			// seller mail
			if($model->balbolo && $model->balbolo->user && $model->balbolo->user->email){
				$to_seller = $model->balbolo->user->email;
				$subject_seller = 'Your have declined your booking';
				$body_seller = 'You have successfully declined your balbolo booking for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/book-in',true) .'.<br><br>Thanks';
				
				mail($to_seller,$subject_seller,$body_seller,$headers);
			}
			
			//buyer mail
			if($model->user && $model->balbolo){
				$to_Buyer = $model->user->email;
				$sublect_buyer = 'Your Booking has been declined';
				$body_buyer = 'Your booking has been declined by balbolo seller for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/index',true) .' .<br><br>Thanks';
				
				mail($to_Buyer,$sublect_buyer,$body_buyer,$headers);
			}
		
			$user = User::findOne($model->user_id);
			if($user){
				$user->profile->points = $user->profile->points + $model->total_point;
				$user->profile->save();
				Yii::$app->session->setFlash('success','Booking Declined Successfully');
			}
		}else{
			Yii::$app->session->setFlash('error','Unable To Decline Booking');
		}

        return $this->redirect(['book-in']);
    }
	
	public function actionDeclineBooking($id)
    {
        $model = $this->findModel($id);
		$model->confirm_status = '2';
		if($model->save()){
		
			$from = Yii::$app->params['adminEmail'];
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: " . $from;
			
			// seller mail
			if($model->balbolo && $model->balbolo->user && $model->balbolo->user->email){
				$to_seller = $model->balbolo->user->email;
				$subject_seller = 'Your have declined your booking';
				$body_seller = 'You have successfully declined your balbolo booking for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/book-in',true) .'.<br><br>Thanks';
				
				mail($to_seller,$subject_seller,$body_seller,$headers);
			}
			
			//buyer mail
			if($model->user && $model->balbolo){
				$to_Buyer = $model->user->email;
				$sublect_buyer = 'Your Booking has been declined';
				$body_buyer = 'Your booking has been declined by balbolo seller for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/index',true) .' .<br><br>Thanks';
				
				mail($to_Buyer,$sublect_buyer,$body_buyer,$headers);
			}
		
			$user = User::findOne($model->user_id);
			if($user){
				$user->profile->points = $user->profile->points + $model->total_point;
				$user->profile->save();
				Yii::$app->session->setFlash('success','Booking Declined Successfully');
			}
		}else{
			Yii::$app->session->setFlash('error','Unable To Decline Booking');
		}

        return $this->redirect(['index']);
    }
	
	// by owner
	public function actionCancel($id){
		$model = $this->findModel($id);
		if($model){
			
			$user_id = $model->user->id;
			$user = User::findOne($user_id);
			if($user){
				$user->profile->points = $user->profile->points + $model->total_point;
				$user->profile->save();
			}
			
			$user_id2 = $model->balbolo->user->id;
			$user2 = User::findOne($user_id2);
			if($user2){
				$user2->profile->points = $user2->profile->points - $model->total_point;
				$user2->profile->save();
			}
		
			$from = Yii::$app->params['adminEmail'];
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: " . $from;
		
			// seller mail
			if($model->balbolo && $model->balbolo->user && $model->balbolo->user->email){
				$to_seller = $model->balbolo->user->email;
				$subject_seller = 'Your have Cancelled your booking';
				$body_seller = 'You have successfully Cancelled your balbolo booking for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/book-in',true) .'.<br><br>Thanks';
				
				mail($to_seller,$subject_seller,$body_seller,$headers);
			}
			
			//buyer mail
			if($model->user && $model->balbolo){
				$to_Buyer = $model->user->email;
				$sublect_buyer = 'Your Booking has been Cancelled';
				$body_buyer = 'Your booking has been Cancelled by balbolo seller for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/index',true) .' .<br><br>Thanks';
				
				mail($to_Buyer,$sublect_buyer,$body_buyer,$headers);
			}
			
			Yii::$app->session->setFlash('success','Booking cancelled successfully');
			
			//$model->confirm_status = '1';
			$model->cancel_status = '1';
			$model->save();
		
			return $this->redirect(['book-in']);
		}
	}
	
	// by user
	public function actionCancelBookings($id){
		$model = $this->findModel($id);
		if($model){

			$user_id = $model->user->id;
			$user = User::findOne($user_id);
			if($user){
				$user->profile->points = $user->profile->points + $model->total_point;
				$user->profile->save();
			}
			
			$user_id2 = $model->balbolo->user->id;
			$user2 = User::findOne($user_id2);
			if($user2){
				$user2->profile->points = $user2->profile->points - $model->total_point;
				$user2->profile->save();
			}
		
			$from = Yii::$app->params['adminEmail'];
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: " . $from;
		
			// seller mail
			if($model->balbolo && $model->balbolo->user && $model->balbolo->user->email){
				$to_seller = $model->balbolo->user->email;
				$subject_seller = 'Your booking has been Cancelled';
				$body_seller = 'User has declined your balbolo booking for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/book-in',true) .'.<br><br>Thanks';
				
				mail($to_seller,$subject_seller,$body_seller,$headers);
			}
			
			//buyer mail
			if($model->user && $model->balbolo){
				$to_Buyer = $model->user->email;
				$sublect_buyer = 'Your Booking has been Cancelled';
				$body_buyer = 'You have successfully Cancelled for "'. $model->balbolo->name .'" . you can check listing on '. Url::toRoute('bookings/index',true) .' .<br><br>Thanks';
				
				mail($to_Buyer,$sublect_buyer,$body_buyer,$headers);
			}
			
			Yii::$app->session->setFlash('success','Booking cancelled successfully');
		
			$model->delete();
			return $this->redirect(['upcoming']);
		}
	}
	
	public function actionRate($id){
		$model = $this->findModel($id);
		$rating = new Ratings();
		//$ratingdetails = RatingBrief::findOne($id);
		$ratingdetails = new RatingBrief();
		
		if($rating->load(Yii::$app->request->post()) && $ratingdetails->load(Yii::$app->request->post())) {
		
			$rating_total = $ratingdetails->rating_communication + $ratingdetails->rating_accuracy + $ratingdetails->rating_cleaning + $ratingdetails->rating_location + $ratingdetails->rating_value + $ratingdetails->rating_checkin;
			
			$rating->rating_avg = $rating_total / 6;
		
			if($rating->validate() && $ratingdetails->validate()){
				if($rating->save()){
					$ratingdetails->rating_id = $rating->id;
					$ratingdetails->save();
					return $this->redirect(['index']);
				}
			}
		}
		
		return $this->render('rate',[
			'model' => $model,
			'model1' => $rating,
			'model2' => $ratingdetails
		]);
	}
	
	
	
	protected function isUserCanCancel($seller=false)
    {
		if($this->findModel(Yii::$app->request->get('id'))->confirm_status != 0){
		
			$bk_model = $this->findModel(Yii::$app->request->get('id'));
			
			if(!$seller){
				if($bk_model->user_id == Yii::$app->user->id){
					return true;
				}else{
					return false;
				}
			}
			
			return $bk_model->balbolo->user_id == Yii::$app->user->id;
		}
		
		return false;
    }
	
	protected function isUserAuthor($seller=false)
    {   
		if($this->findModel(Yii::$app->request->get('id'))->confirm_status == 0){
		
			$bk_model = $this->findModel(Yii::$app->request->get('id'));
			
			if($seller){
				if($bk_model->user_id == Yii::$app->user->id){
					return true;
				}
			}
			
			return $bk_model->balbolo->user_id == Yii::$app->user->id;
		}
		
		return false;
    }
	
	protected function canRate()
    {
		if($this->findModel(Yii::$app->request->get('id'))->user_id == Yii::$app->user->id){
			$model = $this->findModel(Yii::$app->request->get('id'));
			//print_r($model->rating);
			return (!$model->rating) ? true : false;
		}
		
		return false;
    }
}
