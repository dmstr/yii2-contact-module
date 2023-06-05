<?php

use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\models\ContactTemplate;
use dmstr\modules\prototype\widgets\TwigWidget;

/**
 * @var $schema string
 * @var $model ContactLog
 */
?>

<?=
 TwigWidget::widget(
    [
        'key' => ContactTemplate::TMPL_PREFIX . $schema . ContactTemplate::TMPL_SEND_SUFFIX,
        'params' => [
            'model' => $model,
        ]
    ]
)
?>



