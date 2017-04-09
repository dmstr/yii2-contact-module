<?php
/**
 * /app/src/../runtime/giiant/fccccf4deb34aed738291a9c38e87215
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\contact\models\ContactLog $model
 */
$this->title = Yii::t('cruds', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Contact Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud contact-log-create">

    <h1>
        <?php echo Yii::t('models', 'Contact Log') ?>
        <small>
                        <?php echo $model->id ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo             Html::a(
	Yii::t('cruds', 'Cancel'),
	\yii\helpers\Url::previous(),
	['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
