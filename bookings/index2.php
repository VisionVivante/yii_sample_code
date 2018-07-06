<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use frontend\models\Posts2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Orders';
$this->params['breadcrumbs'][] = $this->title;

$user_post = ArrayHelper::map(Posts2::find()->where(['user_id' => Yii::$app->user->id])->all(),'name','name');

?>
<style>
.bookings-index a
	{
		color:black;
	}
</style>
<div class="bookings-index">

    <h1 style="margin:0;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<br>
	 <div class="table table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
			[
				'attribute'=>'balbolo_name',
				/* 'filter' => $user_post,
				'format'=>'text',//raw, html
				'content'=>function($data){
					return $data->getBalboloname();
				}, */
				//'headerOptions'=>['style'=>'width:15%']
			],
			[
				'attribute'=>'username',
				//'filter' => $user_post,
				'format'=>'text',//raw, html
				'content'=>function($data){
					return $data->getUsername();
				}
			],
            //'user_id',
            //'unit_points',
            //'confirm_status',
			[
				'attribute'=>'confirm_status',
				'filter' => ['0'=>'Pending','1'=>'Confirmed','2'=>'Cancelled'],
				'value' => function($model, $key, $index)
				{
					return ($model->confirm_status == '1') ? 'Confirmed' : (($model->confirm_status == 2) ? 'Cancelled' :'Pending');
				},
			],
			[
				'attribute'=>'guest',
				'headerOptions'=>['style'=>'width:8%']
			],
			[
				'attribute'=>'unit',
				'headerOptions'=>['style'=>'width:8%']
			],
			[
				//'attribute'=>'rejection',
				'label' => 'Cancel',
				'format' => 'raw',
				'value' => function($data){ return ($data->attributes['confirm_status'] == '1') ? '<a href="'. Url::to(['cancel','id'=>$data->attributes['id']]) .'" class="cancelb"><button>Cancel</button></a>' : ''; },
			],
            /* 'guest',
            'unit', */
            //'total_point',
            'start_date',
            // 'created_at',
            [
				'class' => 'yii\grid\ActionColumn',
				'buttons' => [
					'accept' => function ($url,$model) {
						if(!$model->confirm_status == 0){
							return false;
						}
						return Html::a(
							'<span class="glyphicon glyphicon-ok"></span>',
							$url,
							[
								'title' => 'Accept',
								'data-confirm' => \Yii::t('yii', 'Are you sure to accept this balbolo?'),
								'data-method' => 'post',
								'data-pjax' => '0',
							]
						);
					},
					'decline' => function ($url,$model) {
						if(!$model->confirm_status == 0){
							return false;
						}
						return Html::a(
							'<span class="glyphicon glyphicon-remove"></span>',
							$url,
							[
								'title' => 'Decline',
								'data-confirm' => \Yii::t('yii', 'Are you sure to decline this balbolo?'),
								'data-method' => 'post',
								'data-pjax' => '0',
							]
						);
					},
					'view-order' => function ($url,$model) {
						return Html::a(
							'<span class="glyphicon glyphicon-eye-open"></span>',
							$url,
							[
								'title' => 'View',
							]
						);
					},
                ],
				'template'=>'{view-order}{accept}{decline}',
				'headerOptions'=>['style'=>'width:7%']
			],
        ],
    ]); ?>
</div>
</div>
<?php

$this->registerJs("
	jQuery('.cancelb').click(function(e){
		e.preventDefault();
		if(confirm('Are you want to Cancel this booking ?')){
			window.location.href = jQuery(this).attr('href');
		}
	})
",$this::POS_READY);