<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookingsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bookings-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'balbolo_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'unit_points') ?>

    <?= $form->field($model, 'confirm_status') ?>

    <?php // echo $form->field($model, 'guest') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'total_point') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
