<?php
/**
 * /app/runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\contact\models\ContactTemplate $model
 */
$this->title = Yii::t('models', 'Contact Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Contact Template'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Edit');
?>
<div class="giiant-crud contact-template-update">

    <h1>
        <?php echo Yii::t('models', 'Contact Template') ?>
        <small>
                        <?php echo Html::encode($model->name) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('cruds', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
