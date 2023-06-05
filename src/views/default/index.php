<?php

use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\models\ContactTemplate;
use dmstr\modules\prototype\widgets\TwigWidget;

/**
 * @var $schema string
 * @var $model ContactLog
 * @var array $schemaData
 */
?>

<?= TwigWidget::widget(
    [
        'key' => ContactTemplate::TMPL_PREFIX . $schema,
        'params' => [
            'model' => $model,
            'schema' => $schemaData,
        ],
    ]
) ?>



