<?php

namespace dmstr\modules\contact\models;

use \dmstr\modules\contact\models\base\ContactTemplate as BaseContactTemplate;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\validators\EmailValidator;

/**
 * This is the model class for table "app_dmstr_contact_template".
 */
class ContactTemplate extends BaseContactTemplate
{

    const TMPL_PREFIX = 'contact:';
    const TMPL_SEND_SUFFIX = ':send';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_contact_template}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()')
        ];
        return $behaviors;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules['single_mail_value'] = [['from_email', 'reply_to_email', 'return_path'], 'email', 'skipOnEmpty' => true];
        $rules['multi_mail_value'] = [['to_email'], 'validateMultiMailValues', 'skipOnEmpty' => true];
        return $rules;
    }

    public function attributeHints()
    {
        $hints = parent::attributeHints();
        $hints['to_email'] = Yii::t('contact', 'One or more email addresses separated by comma');
        $hints['reply_to_email'] = Yii::t('contact', 'If set, this will be used as "Reply-To" for ALL Emails');
        $hints['return_path'] = Yii::t('contact', 'If set, this will be used as "Return-Path" (address for bounce mails)');
        $hints['reply_to_schema_property'] = Yii::t('contact', 'Email property name from json schema that should be used as "Reply-To" (if reply_to_email is empty)');
        return $hints;
    }

    /**
     * @return array
     */
    public static function optsCaptcha()
    {
        return [
            0 => \Yii::t('contact', 'No'),
            1 => \Yii::t('contact', 'Yes'),
        ];
    }

    /**
     * inline validator to check comma separated mail addresses
     *
     * @param $attribute
     * @param $params
     * @param $validator
     *
     * @return bool|void
     */
    public function validateMultiMailValues($attribute, $params, $validator)
    {

        $emailValidator = new EmailValidator();
        $emailValidator->attributes = $attribute;

        $values = array_filter(array_map('trim', explode(',', $this->$attribute)));
        if (empty($values)) {
            return (bool)$validator->skipOnEmpty;
        }

        foreach ($values as $value) {
            if (! $emailValidator->validate($value)) {
                $this->addError($attribute, Yii::t('yii', '{attribute} is not a valid email address.', ['attribute' => $this->getAttributeLabel($attribute)]));
                return false;
            }
        }
    }

}
