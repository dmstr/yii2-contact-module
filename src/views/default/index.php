<?php

use dmstr\modules\contact\controllers;

/**
 *
 * @var $params dmstr\modules\contact\controllers\DefaultController;
 *
 */
?>

<?= \dmstr\modules\prototype\widgets\TwigWidget::widget(
    [
        'key' => 'contact:'.$schema,
        'params' => [
            'model' => $model,
            'schema' => $schemaData,
            'success' => $success,
        ],
    ]
) ?>



