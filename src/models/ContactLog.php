<?php

namespace dmstr\modules\contact\models;

use dmstr\modules\contact\models\base\ContactLog as BaseContactLog;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use yii\swiftmailer\Message;
use yii\web\HttpException;

/**
 * This is the model class for table "app_dmstr_contact_log".
 *
 * @property mixed $emailSubject
 * @property ContactTemplate $contactTemplate
 */
class ContactLog extends BaseContactLog
{


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


        $contactTemplate = $this->contactTemplate;

        $message = Yii::$app->mailer->compose();
        $message->setFrom($contactTemplate->from_email);
        if (!empty($contactTemplate->reply_to_email)) {
            $message->setReplyTo($contactTemplate->reply_to_email);
        }
        $message->setTo($contactTemplate->to_email);
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
