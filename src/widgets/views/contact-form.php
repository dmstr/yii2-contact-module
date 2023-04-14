<?php
/**
 * @var string $submitUrl
 * @var string $captchaUrl
 * @var ContactTemplate $contactTemplate
 * @var ContactFormModel $model
 * @var array $schemaData
 * @var array $jsonEditorClientOptions
 */

use dmstr\jsoneditor\JsonEditorWidget;
use dmstr\modules\contact\models\ContactForm as ContactFormModel;
use dmstr\modules\contact\models\ContactTemplate;
use dmstr\modules\prototype\widgets\TwigWidget;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="contact-form-widget" id="contact-form-<?php echo $contactTemplate->id ?>">
    <?php

    echo TwigWidget::widget([
        'key' => 'contact-form-widget:' . $contactTemplate->name,
        'params' => [
            'model' => $model,
            'schema' => $schemaData
        ],
    ]);

    $form = ActiveForm::begin([
        'action' => $submitUrl
    ]);

    echo $form->field($model, 'json')->widget(JsonEditorWidget::class, [
        'clientOptions' => $jsonEditorClientOptions,
        'schema' => $schemaData,
    ]);

    if ($model->getScenario() === ContactFormModel::SCENARIO_CAPTCHA) {
        echo $form->field($model, 'captcha')->widget(Captcha::class, [
            'captchaAction' => $captchaUrl
        ]);
    }

    echo $form->errorSummary($model)
    ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('contact', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
