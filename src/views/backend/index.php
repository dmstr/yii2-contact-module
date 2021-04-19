<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use Yii;

?>
<h1><?= Yii::t('contact', 'Overview') ?></h1>

<p>
    <?= Html::a(Yii::t('contact', 'Templates'), ['crud/contact-template']) ?>
</p>

<p>
    <?= Html::a(Yii::t('contact', 'Logs'), ['crud/contact-log']) ?>
</p>

<p>
    <?= Html::a(Yii::t('contact', 'Export'), ['export/index']) ?>
</p>
