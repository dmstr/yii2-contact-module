<?php

namespace dmstr\modules\contact\controllers;

use dmstr\modules\contact;
use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\models\ContactModel;
use yii;
use yii\helpers\Url;
use yii\web\Controller;
use JsonSchema\Validator;
use yii\helpers\Json;

/**
 * Default controller for the `contact` module
 */
class DefaultController extends Controller
{

    protected $schemaSettings = [];

    public function init()
    {
        $this->layout = $this->module->frontendLayout;
        parent::init();
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($schema)
    {
        $success = null;
        $formModel = new contact\models\ContactForm();
        $formModel->schema = $schema;

        $this->setSettings($schema);
        $this->validateSettings($schema);

        if ($formModel->load($_POST) && $formModel->validate()) {

            $dbModel = new ContactLog();
            $dbModel->schema = $schema;
            $dbModel->json = $formModel->json;

            if (! $dbModel->save()) {
                throw new yii\web\HttpException(500, "Message could not be saved!");
            }

            Yii::$app->session->set('contact_form_id', $dbModel->id);

            if ($this->sendMessage($dbModel)) {
                $this->sendConfirmMessage($dbModel);
                $success = true;
                Yii::$app->session->addFlash('success', 'Mail sent.');
            } else {
                Yii::$app->session->addFlash('error', 'Mail not sent.');
            }
            $this->redirect('/contact/default/done?schema=' . $schema);
        }

        $schemaData = yii\helpers\Json::decode($this->schemaSettings['schema']->scalar);

        return $this->render(
            'index',
            [
                'model' => $formModel,
                'schema' => $schema,
                'schemaData' => $schemaData,
                'success' => $success,
            ]
        );
    }

    public function actionDone($schema)
    {

        $contact_form_id = Yii::$app->session->get('contact_form_id');
        $obj = false;

        if (!empty($contact_form_id)) {
            $model = ContactLog::find()->where(['id' => $contact_form_id])->one();
            $obj = Json::decode($model->json, false);

        #var_dump($obj); exit;
        }

        return $this->render(
            'form_send',
            [
                'schema' => $schema,
                'model' => $obj,
            ]
        );

    }

    /**
     * Send message via mailer component
     *
     * @param array $params
     *
     * $to is set via settings module, section=>'contact', key=>'mail_to', value=>desired mail address
     *
     * @return bool
     */
    protected function sendMessage($model)
    {
        $data = yii\helpers\Json::decode($model->json);

        if (empty($data)) {
            throw new yii\web\HttpException(500, 'Empty data');
        }

        $text = $this->dataValue2txt($data);
        #file_put_contents('/app/runtime/mail/txt', $text);
        $message = Yii::createObject('yii\swiftmailer\Message');

        $message->to = $this->schemaSettings['toEmail'];
        $message->from = $this->schemaSettings['fromEmail'];
        $message->subject = empty($this->schemaSettings['subject']) ? "Contact Form - ".getenv('APP_TITLE') : $this->schemaSettings['subject'];
        $message->textBody = $text;

        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        return $mailer->send($message);
    }

    protected function sendConfirmMessage($model)
    {
        // if no confirmMail templ is set as setting, nothing to send, so return
        if (empty($this->schemaSettings['confirmMail'])) {
            return;
        }

        $data = yii\helpers\Json::decode($model->json);
        #yii\helpers\VarDumper::dump($data['Participant']['Email'], 10,1); exit;

        // TODO: path to mail shouldn't be hardcoded....
        if (empty($data['Participant']['Email'])) {
            return;
        }

        $message = Yii::createObject('yii\swiftmailer\Message');
        $message->from = $this->schemaSettings['fromEmail'];
        $message->to = $data['Participant']['Email'];
        $message->textBody = $this->schemaSettings['confirmMail'];
        $message->subject = empty($this->schemaSettings['subject']) ? "Contact Form - ".getenv('APP_TITLE') : $this->schemaSettings['subject'];

        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        return $mailer->send($message);

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

        $text = "";
        ++$level;
        $prefix = str_repeat(' ', $level*2);

        if (! is_array($data)) {
            return $text;
        }

        foreach ($data as $key => $value) {

            if (is_array($value)){
                $valueText = "\n" . $this->dataValue2txt($value, $level);
            } else {
                $valueText = trim($value);
            }
            $text .= $prefix . trim($key) . ': ' . $valueText . "\n";

        }

        return $text;

    }

    protected function setSettings($schema)
    {
        $this->schemaSettings['schema'] = Yii::$app->settings->get($schema.'.schema', 'contact');
        $this->schemaSettings['fromEmail'] = Yii::$app->settings->get($schema.'.fromEmail', 'contact');
        $this->schemaSettings['toEmail'] = Yii::$app->settings->get($schema.'.toEmail', 'contact');
        $this->schemaSettings['subject'] = Yii::$app->settings->get($schema.'.subject', 'contact');
        $this->schemaSettings['confirmMail'] = Yii::$app->settings->get($schema.'.confirmMail', 'contact');

        if (!is_object($this->schemaSettings['schema'])) {
            throw new yii\base\Exception('Schema setting is not an object.');
        }
        #var_dump($this->schemaSettings['schema']);
    }

    private function validateSettings($schema)
    {

        return true;

        // TODO validate against custom defined schema
        $validator = new Validator();
        $obj = Json::decode(\Yii::$app->settings->get($schema.'.settings', 'contact')->scalar, true);

       # echo '<pre>'; var_dump($obj); exit;

        $validator->check($this->schemaSettings, $obj);

        if ($validator->getErrors()) {
            foreach ($validator->getErrors() as $error) {
                \Yii::error(__CLASS__ .':' . __METHOD__, "{$error['property']}: {$error['message']}");
            }
        }
    }

}
