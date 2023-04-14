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
        'id' => 'contact-form-' . $contactTemplate->id,
        'action' => $submitUrl,
        'enableClientValidation' => false,
        'enableClientScript' => false
    ]);

    echo $form->field($model, 'json')->widget(JsonEditorWidget::class, [
        'id' => 'contactFormEditor' . $contactTemplate->id,
        'options' => [
            'id' => 'contactFormEditorJson' . $contactTemplate->id
        ],
        'containerOptions' => [
            'id' => 'contact-form-json-editor-container-' . $contactTemplate->id,
        ],
        'clientOptions' => $jsonEditorClientOptions,
        'schema' => $schemaData,
    ])->label(false);

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
