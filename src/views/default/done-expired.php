<?php

use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\models\ContactTemplate;
use dmstr\modules\prototype\widgets\TwigWidget;

/**
 * @var $schema string
 * @var $model ContactLog
 */
?>

<div class="container text-center">
    <div class="row">
    <div class=" col-xs-12 alert alert-success">
        <?= Yii::t('contact', "The message has already been sent.") ?>
    </div>
    </div>
</div>
