<?php

use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\prototype\widgets\TwigWidget;

/**
 * @var $schema string
 * @var $model ContactLog
 */
?>

<?=
 TwigWidget::widget(
    [
        'key' => 'contact:' . $schema . ':send',
        'params' => [
            'model' => $model,
        ]
    ]
)
?>



