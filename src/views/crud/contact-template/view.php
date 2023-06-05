<?php
/**
 * /app/runtime/giiant/d4b4964a63cc95065fa0ae19074007ee
 *
 * @package default
 */


use dmstr\modules\contact\models\ContactTemplate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\contact\models\ContactTemplate $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Contact Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Contact Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud contact-template-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?php echo \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?php echo Yii::t('models', 'Contact Template') ?>
        <small>
            <?php echo Html::encode($model->name) ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?php echo Html::a(
	'<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
	[ 'update', 'id' => $model->id],
	['class' => 'btn btn-info']) ?>

            <?php echo Html::a(
	'<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
	['create', 'id' => $model->id, 'ContactTemplate'=>$copyParams],
	['class' => 'btn btn-success']) ?>

            <?php echo Html::a(
	'<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
	['create'],
	['class' => 'btn btn-success']) ?>

            <?php
            $tmplName = ContactTemplate::TMPL_PREFIX . $model->name;
            if ($twig = \dmstr\modules\prototype\models\Twig::findOne(['key' => $tmplName])) {
                echo Html::a('<span class="glyphicon glyphicon-file"></span> '
                             . Yii::t('cruds', 'Form Twig'), ['/prototype/twig/view', 'id' => $twig->id], ['class'=>'btn btn-success']);
            } else {
                echo Html::a('<span class="glyphicon glyphicon-file"></span> '
                             . Yii::t('cruds', 'Create Form Twig'), ['/prototype/twig/create', 'Twig[key]' => $tmplName], ['class'=>'btn btn-warning']);

            }
            ?>

            <?php
            $tmplName = ContactTemplate::TMPL_PREFIX . $model->name . ContactTemplate::TMPL_SEND_SUFFIX;
            if ($twig = \dmstr\modules\prototype\models\Twig::findOne(['key' => $tmplName])) {
                echo Html::a('<span class="glyphicon glyphicon-file"></span> '
                             . Yii::t('cruds', 'Send Twig'), ['/prototype/twig/view', 'id' => $twig->id], ['class'=>'btn btn-success']);
            } else {
                echo Html::a('<span class="glyphicon glyphicon-file"></span> '
                             . Yii::t('cruds', 'Create Send Twig'), ['/prototype/twig/create', 'Twig[key]' => $tmplName], ['class'=>'btn btn-warning']);

            }
            ?>
        </div>

        <div class="pull-right">
            <?php echo Html::a('<span class="glyphicon glyphicon-eye-open"></span> '
                               . Yii::t('cruds', 'View in Frontend'), ['/contact/default', 'schema' => $model->name], ['class'=>'btn btn-default']) ?>

            <?php echo Html::a('<span class="glyphicon glyphicon-list"></span> '
	. Yii::t('cruds', 'Full list'), ['index'], ['class'=>'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('dmstr\modules\contact\models\ContactTemplate'); ?>


    <?php echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'name:ntext',
			'from_email:email',
			'to_email:email',
            'reply_to_email:email',
            'reply_to_schema_property:ntext',
            'return_path:email',
            'email_subject:ntext',
			'captcha',
            [
                'attribute' => 'form_schema',
                'format' => 'raw',
                'value' => "<pre>" . htmlspecialchars($model->form_schema) . "</pre>",
            ],
            'created_at',
            'updated_at',
		],
	]); ?>


    <hr/>

    <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('cruds', 'Delete'), ['delete', 'id' => $model->id],
	[
		'class' => 'btn btn-danger',
		'data-confirm' => '' . Yii::t('cruds', 'Are you sure to delete this item?') . '',
		'data-method' => 'post',
	]); ?>
    <?php $this->endBlock(); ?>



    <?php echo Tabs::widget(
	[
		'id' => 'relation-tabs',
		'encodeLabels' => false,
		'items' => [
			[
				'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
				'content' => $this->blocks['dmstr\modules\contact\models\ContactTemplate'],
				'active'  => true,
			],
		]
	]
);
?>
</div>
