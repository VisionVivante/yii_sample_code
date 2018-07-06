<?php

namespace frontend\controllers;

use Yii;
use common\models\Forms;
use frontend\models\Category;
use backend\models\FormsSearch;
use frontend\models\PostMeta;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FormsController implements the CRUD actions for Forms model.
 */
class FormsController extends Controller
{
	
	public $layout = 'dashboard';
	public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
				'only' => ['index','create','update','view','delete','getform'],
                'rules' => [
                    [
                        'actions' => ['index','create','update','view','delete','getform'],
                        'allow' => true,
                        'roles' => ['@'],
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
     * Lists all Forms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FormsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionGetform(){
		if(Yii::$app->request->post()){
			$subcat = $_POST['subcat'];
			$data = Forms::find()->where(['category_id' => $subcat])->one();
			if($data){
				//print_r($data->attributes['form_data']);die;
				return $this->renderPartial('formcreate', [
					'data' => $data->attributes['form_data'],
					'label' => $data->attributes['labelunit'],
					//'dataProvider' => $dataProvider,
				]);
			}
			
			return '';
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionLoadform(){
		if(Yii::$app->request->post()){
			$subcat = $_POST['subcat'];
			$post_id = $_POST['post_id'];
			$data = Forms::find()->where(['category_id' => $subcat])->one();
			$data2 = PostMeta::find()->where(['post_id' => $post_id])->all();
			if($data){
				//print_r($data->attributes['form_data']);die;
				return $this->renderPartial('formload', [
					'data' => $data->attributes['form_data'],
					'label' => $data->attributes['labelunit'],
					'data2' => $data2,
					//'dataProvider' => $dataProvider,
				]);
			}
			
			return '';
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}

    /**
     * Displays a single Forms model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Forms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Forms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Forms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		$model->main_category = Category::getCategoryParentId($model->category_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Forms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Forms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Forms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Forms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
