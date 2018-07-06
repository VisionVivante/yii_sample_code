<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Booking';
$this->params['breadcrumbs'][] = $this->title;
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
				'filter' => ['0'=>'Pending', '1'=>'Confirmed', '2'=>'Cancelled'],
				'value' => function($model, $key, $index)
				{
					return ($model->confirm_status == '1') ? 'Confirmed' : (($model->confirm_status == 2) ? 'Cancelled' :'Pending');
				},
			],
            'guest',
            'unit',
            //'total_point',
            'start_date',
            // 'created_at',

            [
				'class' => 'yii\grid\ActionColumn',
				'buttons' => [
					'rate' => function ($url,$model) {
						if(!$model->rating){
							if($model->confirm_status == 1){
								return Html::a(
									'<span class="glyphicon glyphicon-star"></span>',
									$url,
									[
										'title' => 'Rate this',
									]
								);
							}
						}
						return '';
					},
                ],
				'template'=>'{view}{rate}',
				'headerOptions'=>['style'=>'width:7%']
			],
        ],
    ]); ?>
</div>
</div>
