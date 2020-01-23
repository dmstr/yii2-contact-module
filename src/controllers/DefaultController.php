<?php

namespace dmstr\modules\contact\controllers;

use dmstr\modules\contact\models\ContactForm;
use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\Module;
use JsonSchema\Validator;
use yii;
use yii\helpers\Json;
use yii\swiftmailer\Message;
use yii\validators\EmailValidator;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `contact` module
 *
 * @property mixed $settings
 * @property Module $module
 */
class DefaultController extends Controller
{

    protected $schemaSettings = [];

    const CONTACT_FORM_ID_KEY = 'contact:formId';

    public function init()
    {
        $this->layout = $this->module->frontendLayout;
        parent::init();
    }

    /**
     * Renders the index view for the module
     *
     * @param $schema
     *
     * @return string
     * @throws HttpException
     * @throws yii\base\Exception
     * @throws yii\base\InvalidConfigException
     */
    public function actionIndex($schema)
    {
        $formModel = new ContactForm();
        $formModel->schema = $schema;

        if ($this->setSettings($schema) === false) {
            throw new NotFoundHttpException('Page not found.');
        }

        $this->validateSettings($schema);

        if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {

            $dbModel = new ContactLog();
            $dbModel->schema = $schema;
            $dbModel->json = $formModel->json;

            if (!$dbModel->save()) {
                throw new HttpException(500, 'Your message could not be sent.');
            }
            Yii::$app->session->set(self::CONTACT_FORM_ID_KEY, $dbModel->id);

            if ($this->sendMessage($dbModel)) {
                $this->sendConfirmMessage($dbModel);
                Yii::$app->session->addFlash('success', Yii::t('contact', 'Your message was successfully sent.'));
            } else {
                Yii::$app->session->addFlash('error', Yii::t('contact', 'Your message could not be sent.'));
            }
            $this->redirect(['/contact/default/done', 'schema' => $schema]);
        }

        $schemaData = Json::decode($this->schemaSettings['schema']->scalar);

        return $this->render(
            'index',
            [
                'model' => $formModel,
                'schema' => $schema,
                'schemaData' => $schemaData
            ]
        );
    }

    public function actionDone($schema)
    {
        $model = ContactLog::find()->where(['id' => Yii::$app->session->get(self::CONTACT_FORM_ID_KEY)])->one();

        if ($model === null) {
            throw new ForbiddenHttpException(Yii::t('contact', 'You are not allowed to access this page directly.'));
        }

        Yii::$app->session->set(self::CONTACT_FORM_ID_KEY,null);

        return $this->render(
            'done',
            [
                'schema' => $schema,
                'model' => Json::decode($model->json, false),
            ]
        );

    }

    /**
     * Send message via mailer component
     *
     * @param $model
     *
     * @return bool
     * @throws HttpException
     * @throws yii\base\InvalidConfigException
     */
    protected function sendMessage($model)
    {
        $data = Json::decode($model->json);

        if (empty($data)) {
            throw new HttpException(422, Yii::t('contact', 'No processable data was sent.'));
        }

        $text = $this->dataValue2txt($data);
        $message = Yii::createObject(Message::class);

        $message->to = $this->schemaSettings['toEmail'];
        $message->from = $this->schemaSettings['fromEmail'];
        $message->subject = empty($this->schemaSettings['subject']) ? Yii::t('contact', 'Contact Form - {appName}',
            ['appName' => getenv('APP_TITLE')]) : $this->schemaSettings['subject'];
        $message->textBody = $text;

        $validator = new EmailValidator();
        # set optional ReturnPath Header
        if ((!empty($this->schemaSettings['returnPath'])) && ($validator->validate($this->schemaSettings['returnPath']))) {
            $message->setReturnPath($this->schemaSettings['returnPath']);
        }

        # set optional Reply-To Header if reply_to is set in schema and is valid email address
        if ((!empty($data['reply_to'])) && ($validator->validate($data['reply_to']))) {
            $message->replyTo = $data['reply_to'];
        }

        return Yii::$app->mailer->send($message);
    }

    protected function sendConfirmMessage($model)
    {
        // if no confirmMail template is set as setting, nothing to send, so return
        if (empty($this->schemaSettings['confirmMail'])) {
            return false;
        }

        $data = Json::decode($model->json);

        # only send mail if reply_to is set in schema and is valid email address
        $validator = new EmailValidator();
        if ((empty($data['reply_to'])) || (!$validator->validate($data['reply_to']))) {
            return false;
        }

        $message = Yii::createObject(Message::class);
        $message->from = $this->schemaSettings['fromEmail'];
        $message->to = $data['reply_to'];
        $message->textBody = $this->schemaSettings['confirmMail'];
        $message->subject = empty($this->schemaSettings['subject']) ? Yii::t('contact', 'Contact Form - {appName}',
            ['appName' => getenv('APP_TITLE')]) : $this->schemaSettings['subject'];

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

    protected function setSettings($schema)
    {
        $this->schemaSettings['schema'] = Yii::$app->settings->get($schema . '.schema', 'contact');
        $this->schemaSettings['fromEmail'] = Yii::$app->settings->get($schema . '.fromEmail', 'contact');
        $this->schemaSettings['toEmail'] = Yii::$app->settings->get($schema . '.toEmail', 'contact');
        $this->schemaSettings['subject'] = Yii::$app->settings->get($schema . '.subject', 'contact');
        $this->schemaSettings['confirmMail'] = Yii::$app->settings->get($schema . '.confirmMail', 'contact');
        $this->schemaSettings['returnPath'] = Yii::$app->settings->get($schema . '.returnPath', 'contact');

        return is_object($this->schemaSettings['schema']);
    }

    private function validateSettings($schema)
    {

        return true;

        // TODO validate against custom defined schema
        $validator = new Validator();
        $obj = Json::decode(\Yii::$app->settings->get($schema . '.settings', 'contact')->scalar, true);
        @
        $validator->check($this->schemaSettings, $obj);

        if ($validator->getErrors()) {
            foreach ($validator->getErrors() as $error) {
                \Yii::error(__CLASS__ . ':' . __METHOD__, "{$error['property']}: {$error['message']}");
            }
        }
    }

}
