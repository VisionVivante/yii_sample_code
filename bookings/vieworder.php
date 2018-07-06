<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bookings */

$this->title = 'Booking : #'.$model->id;
if($model->user_id == Yii::$app->user->id){
	$this->params['breadcrumbs'][] = ['label' => 'My Bookings', 'url' => ['index']];
}else{
	$this->params['breadcrumbs'][] = ['label' => 'My Orders', 'url' => ['book-in']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookings-view">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php if(($model->confirm_status == '1') && (!$model->userrating)){ ?>
	<p><a href="<?= Url::toRoute(['user/rate','username'=>$model->username,'booking_id'=>$model->id]); ?>"><button class="btn">Rate User</button></a></p>
	<?php } ?>
    <!--p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'balboloname',
            //'username',
			[
				'attribute'=>'username',
				'format'=>'raw',
				'value'=>Html::a($model->username, ['user/profile', 'username' => $model->username],['target'=>'_blank']),
			],
            'unit_points',
            //'confirm_status',
			[
				'attribute'=>'confirm_status',
				'value' => ($model->confirm_status == '1') ? 'Confirm': (($model->confirm_status == '2')? 'Decline' :'Pending')
			],
            'guest',
            'unit',
            'total_point',
            'start_date',
            //'created_at',
        ],
    ]) ?>

</div>
