<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BookingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bookings';
//$this->params['breadcrumbs'][] = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookings-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

				//'id',
				//'balbolo_id',
				[
					'attribute'=>'balbolo_name',
					'value' => function($model, $key, $index)
					{
						return ($model->cancel_status == '1') ? $model->balbolo_name . ' (Reverted) ': $model->balbolo_name;
					},
				],
				//'balbolo_name',
				//'username',
				//'user_id',
				//'unit_points',
				//'additional',
				// 'additional_after',
				//'confirm_status',
				/* [
					'attribute'=>'confirm_status',
					'filter' => ['0'=>'Pending', '1'=>'Confirmed', '2'=>'Cancelled'],
					'value' => function($model, $key, $index)
					{
						return ($model->confirm_status == '1') ? 'Confirmed' : (($model->confirm_status == 2) ? 'Cancelled' :'Pending');
					},
				], */
				// 'guest',
				// 'unit',
				'total_point',
				//'comission',
				[
					'attribute'=>'comission',
					'value' => function($model, $key, $index)
					{
						return (($model->total_point * $model->comission) / 100);
					},
				],
				// 'start_date',
				'created_at:datetime',

            /* [
				'class' => 'yii\grid\ActionColumn',
				'buttons' => [
					'view2' => function ($url,$model) {
						return Html::a(
							'<span class="glyphicon glyphicon-eye-open"></span>',
							$url,
							[
								'title' => 'View',
							]
						);
					},
                ],
				'template'=>'{view2}{delete}'
			], */
        ],
    ]); ?>
</div>
