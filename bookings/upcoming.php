<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Balbolos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookings-index">

    <h1 style="margin:0;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<br>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
			[
				'attribute'=>'balbolo_name',
				/* 'format'=>'text',//raw, html
				'content'=>function($data){
					return $data->getBalboloname();
				} */
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
            'guest',
            'unit',
			[
				//'attribute'=>'rejection',
				'label' => 'Cancel',
				'format' => 'raw',
				'value' => function($data){ return ($data->attributes['confirm_status'] == '1') ? '<a href="'. Url::to(['cancel-bookings','id'=>$data->attributes['id']]) .'" class="cancelb"><button>Cancel</button></a>' : ''; },
			],
            //'total_point',
            'start_date',
            // 'created_at',

            [
				'class' => 'yii\grid\ActionColumn',
				'buttons' => [
					'decline-booking' => function ($url,$model) {
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
				],
				'template'=>'{view}{decline-booking}',
				'headerOptions'=>['style'=>'width:7%']
			],
        ],
    ]); ?>
</div>
<?php

$this->registerJs("
	jQuery('.cancelb').click(function(e){
		e.preventDefault();
		if(confirm('Are you want to Cancel this booking ?')){
			console.log('confirmed');
			window.location.href = jQuery(this).attr('href');
		}
	})
",$this::POS_READY);
