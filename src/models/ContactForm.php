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
 */
class ContactForm extends Model
{
    public $schema;
    public $json;

    public function rules()
    {
        return [
            [
                'json',
                function ($attribute, $params) {

                    $validator = new Validator();

                    $schema = $this->schema;
                    $obj = Json::decode(\Yii::$app->settings->get($schema.'.schema', 'contact')->scalar, false);
                    $data = Json::decode($this->{$attribute}, false);
                    $validator->check($data, $obj);

                    if ($validator->getErrors()) {
                        foreach ($validator->getErrors() as $error) {
                            $this->addError($error['property'], "{$error['property']}: {$error['message']}");
                            #\Yii::$app->session->addFlash('error', "{$error['property']}: {$error['message']}");
                        }
                    }

                },
            ],
        ];
    }
}