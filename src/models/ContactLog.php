<?php

namespace dmstr\modules\contact\models;

use dmstr\modules\contact\models\base\ContactLog as BaseContactLog;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\Json;
use yii\validators\EmailValidator;

/**
 * This is the model class for table "app_dmstr_contact_log".
 *
 * @property mixed $emailSubject
 * @property ContactTemplate $contactTemplate
 */
class ContactLog extends BaseContactLog
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()')
        ];
        return $behaviors;
    }

    /**
     * @return ActiveQuery
     */
    public function getContactTemplate()
    {
        return $this->hasOne(ContactTemplate::class, ['id' => 'contact_template_id']);
    }

    public function getEmailSubject()
    {
        $emailSubject = $this->contactTemplate->email_subject;
        return empty($emailSubject) ? Yii::t('contact', 'Contact Form - {appName}',
            ['appName' => getenv('APP_TITLE')]) : $emailSubject;
    }

    /**
     * Send message via mailer component
     *
     * @return bool
     */
    public function sendMessage()
    {

        $validator = new EmailValidator();

        $contactTemplate = $this->contactTemplate;

        $data = Json::decode($this->json);

        $message = Yii::$app->mailer->compose();
        $message->setFrom($contactTemplate->from_email);
        // if reply_to_email is set in template we always use this, will be validated in template model
        if (!empty($contactTemplate->reply_to_email)) {
            $message->setReplyTo($contactTemplate->reply_to_email);
        } else {
            # set optional Reply-To Header from schema property if set in schema and is valid email address
            $reply_to_property = $contactTemplate->reply_to_schema_property;
            if ((!empty($reply_to_property)) && (! empty($data[$reply_to_property])) && ($validator->validate($data[$reply_to_property]))){
                $message->setReplyTo($data[$reply_to_property]);
            }
        }

        # set optional ReturnPath Header, as setReturnPath() is not required by the yii MessageInterface,
        # we first check that Method exists
        if (method_exists($message, 'setReturnPath')) {
            if ((!empty($contactTemplate->return_path)) && ($validator->validate($contactTemplate->return_path))) {
                $message->setReturnPath($contactTemplate->return_path);
            }
        }

        $to = array_filter(array_map('trim', explode(',', $contactTemplate->to_email)));
        $message->setTo($to);
        $message->setSubject($this->emailSubject);
        $message->setTextBody($this->dataValue2txt(Json::decode($this->json)));

        return $message->send();
    }

    /**
     * recursive helper to print structured array as indented txt
     *
     * @param array $data
     * @param int $level
     *
     * @return string
     */
    private function dataValue2txt($data, $level = 0)
    {

        $text = '';
        ++$level;
        $prefix = str_repeat(' ', $level * 2);

        if (!is_array($data)) {
            return $text;
        }

        foreach ($data as $key => $value) {

            if (is_array($value)) {
                $valueText = "\n" . $this->dataValue2txt($value, $level);
            } else {
                $valueText = trim($value);
            }
            $text .= $prefix . trim($key) . ': ' . $valueText . "\n";

        }

        return $text;

    }
}
