<?php

namespace dmstr\modules\contact\models;

use JsonSchema\Validator;
use yii\base\Model;
use yii\helpers\Json;

/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2017 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * -- PUBLIC PROPERTIES --
 *
 * @property int $contact_template_id
 * @property string $json
 * @property string $captcha
 * @property int $gdpr
 * @property-read  bool $schema
 */
class ContactForm extends Model
{
    public const SCENARIO_CAPTCHA = 'captcha';

    public $contact_template_id;
    public $json;
    public $captcha;
    public $gdpr;

    public function getSchema()
    {
        $model = ContactTemplate::findOne($this->contact_template_id);

        if ($model === null) {
            return false;
        }

        return $model->form_schema;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            'contact_template_id',
            'exist',
            'skipOnError' => true,
            'targetClass' => ContactTemplate::class,
            'targetAttribute' => [
                'contact_template_id' => 'id'
            ]
        ];
        $rules[] = [
            'json',
            function ($attribute) {
                $validator = new Validator();
                $obj = Json::decode($this->schema, false);
                $data = Json::decode($this->{$attribute}, false);
                $validator->check($data, $obj);

                if ($validator->getErrors()) {
                    foreach ($validator->getErrors() as $error) {
                        $this->addError($error['property'], "{$error['property']}: {$error['message']}");
                    }
                }
            }
        ];
        $rules [] = [
            'captcha',
            'captcha',
            'captchaAction' => 'contact/default/captcha',
            'on' => self::SCENARIO_CAPTCHA
        ];
        $rules[] = [
            'gdpr',
            'required',
            'requiredValue' => 1,
            'message' => \Yii::t('contact', 'You must agree to the GDPR regulations to proceed')
        ];
        return $rules;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['contact_template_id'] = \Yii::t('contact', 'Contact Template ID');
        $attributeLabels['json'] = \Yii::t('contact', 'JSON');
        $attributeLabels['captcha'] = \Yii::t('contact', 'Captcha');
        $attributeLabels['gdpr'] = \Yii::t('contact', 'GDPR');
        return $attributeLabels;
    }

    public function attributeHints()
    {
        $attributeHints = parent::attributeHints();
        $attributeHints['gdpr'] = \Yii::t('contact', '__GDRP_TEXT__');
        return $attributeHints;
    }
}
