<?php

use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\prototype\widgets\TwigWidget;

/**
 * @var $schema string
 * @var $model ContactLog
 * @var array $schemaData
 */
?>

<?= TwigWidget::widget(
    [
        'key' => 'contact:' . $schema,
        'params' => [
            'model' => $model,
            'schema' => $schemaData,
        ],
    ]
) ?>



