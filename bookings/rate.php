<?php


use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\rating\StarRating;

/* @var $this yii\web\View */
/* @var $model app\models\Bookings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bookings-rate">

<h3>Rate for "<?= $model->balbolo->name;?>"</h3>

<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model1,'brief_desc')->textarea(['rows'=>4]); ?>
	<?= $form->field($model1,'booking_id')->hiddenInput(['value' => Yii::$app->request->get('id')])->label(false); ?>

	<div class="col-sm-3">
		<label>Communication</label>
	</div>
	<div class="col-sm-9">
		<?php
			echo $form->field($model2, 'rating_communication')->widget(StarRating::classname(),[
				'pluginOptions' => [
					'for' => 'rating_communication',
					'size' => 'xs',
					'showClear' => false,
					'defaultCaption' => '{rating} Stars',
					'filledStar' => '<i class="fa fa-star"></i>',
					'emptyStar' => '<i class="fa fa-star-o"></i>',
					'starCaptions' => new JsExpression("function(val){return val == 1 ? val +' Star' : val + ' Stars';}")
				]
			])->label(false);
		?>
	</div>
	<div class="col-sm-3">
		<label>Accuracy</label>
	</div>
	<div class="col-sm-9">
		<?php
			echo $form->field($model2, 'rating_accuracy')->widget(StarRating::classname(),[
				'pluginOptions' => [
					'size' => 'xs',
					'for' => 'rating_accuracy',
					'showClear' => false,
					'defaultCaption' => '{rating} Stars',
					'filledStar' => '<i class="fa fa-star"></i>',
					'emptyStar' => '<i class="fa fa-star-o"></i>',
					'starCaptions' => new JsExpression("function(val){return val == 1 ? val +' Star' : val + ' Stars';}")
				]
			])->label(false);
		?>
	</div>
	<div class="col-sm-3">
		<label>Cleaning</label>
	</div>
	<div class="col-sm-9">
		<?php
			echo $form->field($model2, 'rating_cleaning')->widget(StarRating::classname(),[
				'pluginOptions' => [
					'size' => 'xs',
					'for' => 'rating_cleaning',
					'showClear' => false,
					'defaultCaption' => '{rating} Stars',
					'filledStar' => '<i class="fa fa-star"></i>',
					'emptyStar' => '<i class="fa fa-star-o"></i>',
					'starCaptions' => new JsExpression("function(val){return val == 1 ? val +' Star' : val + ' Stars';}")
				]
			])->label(false);
		?>
	</div>
	<div class="col-sm-3">
		<label>Location</label>
	</div>
	<div class="col-sm-9">
		<?php	
			echo $form->field($model2, 'rating_location')->widget(StarRating::classname(),[
				'pluginOptions' => [
					'size' => 'xs',
					'for' => 'rating_location',
					'showClear' => false,
					'defaultCaption' => '{rating} Stars',
					'filledStar' => '<i class="fa fa-star"></i>',
					'emptyStar' => '<i class="fa fa-star-o"></i>',
					'starCaptions' => new JsExpression("function(val){return val == 1 ? val +' Star' : val + ' Stars';}")
				]
			])->label(false);
		?>
	</div>
	<div class="col-sm-3">
		<label>Value</label>
	</div>
	<div class="col-sm-9">
		<?php
			echo $form->field($model2, 'rating_value')->widget(StarRating::classname(),[
				'pluginOptions' => [
					'size' => 'xs',
					'for' => 'rating_value',
					'showClear' => false,
					'defaultCaption' => '{rating} Stars',
					'filledStar' => '<i class="fa fa-star"></i>',
					'emptyStar' => '<i class="fa fa-star-o"></i>',
					'starCaptions' => new JsExpression("function(val){return val == 1 ? val +' Star' : val + ' Stars';}")
				]
			])->label(false);
		?>
	</div>
	<div class="col-sm-3">
		<label>Check In</label>
	</div>
	<div class="col-sm-9">
		<?php
			echo $form->field($model2, 'rating_checkin')->widget(StarRating::classname(),[
				'pluginOptions' => [
					'size' => 'xs',
					'for' => 'rating_checkin',
					'showClear' => false,
					'defaultCaption' => '{rating} Stars',
					'filledStar' => '<i class="fa fa-star"></i>',
					'emptyStar' => '<i class="fa fa-star-o"></i>',
					'starCaptions' => new JsExpression("function(val){return val == 1 ? val +' Star' : val + ' Stars';}")
				]
			])->label(false);
		?>
	</div>
	<div class="form-group">
        <?= Html::submitButton($model2->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>
