<?php

namespace dmstr\modules\contact\widgets;

use dmstr\modules\contact\models\ContactForm as ContactFormModel;
use dmstr\modules\contact\models\ContactTemplate;
use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;

class ContactForm extends Widget
{
    /**
     * Name of the contact template schema
     *
     * @var string
     */
    public $schemaName;

    /**
     * ID of the contact module. You maybe have to change this if you configured it with another id
     *
     * @var string
     */
    public $contactModuleId = 'contact';

    public $jsonEditorClientOptions = [
        'theme' => 'bootstrap3',
        'disable_collapse' => true,
        'disable_edit_json' => true,
        'disable_properties' => true,
        'no_additional_properties' => true,
        'show_errors' => 'never'
    ];

    public function run()
    {
        // Check if contact template does exist
        $contactTemplate = ContactTemplate::findOne(['name' => $this->schemaName]);

        // What to do if the template does not exist? Maybe just don't display something?
        if ($contactTemplate === null) {
            Yii::warning('Schema "' . $this->schemaName . '" does not exist.');
            return '';
        }

        // create form model instance
        $model = Yii::createObject(ContactFormModel::class);
        $model->setAttributes(['contact_template_id' => $contactTemplate->id]);
        if ($contactTemplate->captcha === 1) {
            $model->setScenario(ContactFormModel::SCENARIO_CAPTCHA);

        }


        return $this->render('contact-form', [
            'model' => $model,
            'submitUrl' => Url::to(['/' . $this->contactModuleId . '/default/index', 'schema' => $this->schemaName]),
            'captchaUrl' => '/' . $this->contactModuleId . '/default/captcha',
            'contactTemplate' => $contactTemplate,
            'schemaData' => Json::decode($contactTemplate->form_schema),
            'jsonEditorClientOptions' => $this->jsonEditorClientOptions
        ]);
    }
}
