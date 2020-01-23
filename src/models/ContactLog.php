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
     * @param $this
     *
     * @return bool
     * @throws HttpException
     * @throws yii\base\InvalidConfigException
     */
    public function sendMessage()
    {
        $data = Json::decode($this->json);

        $text = $this->dataValue2txt($data);
        $message = Yii::createObject(Message::class);

        $contactTemplate = $this->contactTemplate;

        $message->to = $contactTemplate->to_email;
        $message->from = $contactTemplate->from_email;
        $message->subject = $this->emailSubject;
        $message->textBody = $text;


        if (!empty($contactTemplate->reply_to_email)) {
            $message->replyTo = $contactTemplate->reply_to_email;
        }

        return Yii::$app->mailer->send($message);
    }

    public function sendConfirmMessage()
    {
        $contactTemplate = $this->contactTemplate;
        // if no confirmMail template is set as setting, nothing to send, so return
        if (!$contactTemplate->send_confirm_email) {
            return false;
        }

        $message = Yii::createObject(Message::class);

        if (!empty($contactTemplate->reply_to_email)) {
            $message->replyTo = $contactTemplate->reply_to_email;
        }

        $message->from = $contactTemplate->from_email;
        $message->to = $contactTemplate->to_email;
        $message->textBody = $contactTemplate->confirm_email_text;
        $message->subject = $this->emailSubject;

        return Yii::$app->mailer->send($message);

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
