<?php
/**
 * /app/runtime/giiant/eeda5c365686c9888dbc13dbc58f89a1
 *
 * @package default
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\contact\models\search\ContactTemplate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="contact-template-search">

    <?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

    		<?php echo $form->field($model, 'id') ?>

		<?php echo $form->field($model, 'name') ?>

		<?php echo $form->field($model, 'from_email') ?>

		<?php echo $form->field($model, 'reply_to_email') ?>

		<?php echo $form->field($model, 'to_email') ?>

		<?php // echo $form->field($model, 'email_subject') ?>

		<?php // echo $form->field($model, 'form_schema') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
