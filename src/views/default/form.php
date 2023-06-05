<?php
/**
 * @var ContactForm $model
 * @var string $schema
*/

use dmstr\modules\contact\widgets\ContactForm;

echo ContactForm::widget(['schemaName' => $schema,'formModel' => $model]);
