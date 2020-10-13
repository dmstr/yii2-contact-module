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
 * @property bool $schema
 */
class ContactForm extends Model
{
    const SCENARIO_CAPTCHA = 'captcha';

    public $contact_template_id;
    public $json;
    public $captcha;

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
        $rules [] = ['captcha', 'captcha', 'captchaAction' => 'contact/default/captcha', 'on' => self::SCENARIO_CAPTCHA];
        return $rules;
    }
}
