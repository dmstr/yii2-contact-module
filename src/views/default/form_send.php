<?php

use dmstr\modules\contact\controllers;

/**
 *
 * @var $params dmstr\modules\contact\controllers\DefaultController;
 *
 */
?>

<?php

    if ($model) {
        echo \dmstr\modules\prototype\widgets\TwigWidget::widget(
            [
                'key' => 'contact:'.$schema.':send',
                'params' => [
                    'model' => $model,
                ],
            ]
        );
    } else {
        echo '<h1>Danke für Ihre Anmeldung</h1>';
    }

?>



