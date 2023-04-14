<?php

namespace dmstr\modules\contact\controllers\actions;

use dmstr\modules\contact\controllers\DefaultController;
use dmstr\modules\contact\events\MessageEvent;
use dmstr\modules\contact\models\ContactForm;
use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\models\ContactTemplate;
use yii\base\Action;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use Yii;

class SubmitAction extends Action
{

    /**
     * @var string
     */
    public $viewFile = 'index';

    /**
     * Submit a form and handle errors
     *
     * @param $schema
     *
     * @throws HttpException
     * @throws NotFoundHttpException
     * @return string
     */
    public function run($schema)
    {
        $contactSchema = ContactTemplate::findOne(['name' => $schema]);

        if ($contactSchema === null) {
            throw new NotFoundHttpException(Yii::t('contact', 'Page not found.'));
        }

        $contactForm = new ContactForm([
            'contact_template_id' => $contactSchema->id
        ]);

        if ($contactSchema->captcha === 1) {
            $contactForm->scenario = ContactForm::SCENARIO_CAPTCHA;
        }

        if ($contactForm->load($this->controller->request->post()) && $contactForm->validate()) {

            $contactLog = new ContactLog([
                'contact_template_id' => $contactSchema->id,
                'json' => $contactForm->json,
            ]);

            if (!$contactLog->save()) {
                throw new HttpException(500, Yii::t('contact', 'Your message could not be sent.'));
            }

            Yii::$app->session->set(DefaultController::CONTACT_FORM_ID_KEY, $contactLog->id);

            $event = new MessageEvent();
            $event->model = $contactLog;
            $this->controller->trigger(MessageEvent::EVENT_BEFORE_MESSAGE_SENT, $event);

            if ($contactLog->sendMessage()) {
                Yii::$app->session->addFlash('success', Yii::t('contact', 'Your message was successfully sent.'));
                $this->controller->redirect(['done', 'schema' => $schema]);

                $this->controller->trigger(MessageEvent::EVENT_AFTER_MESSAGE_SENT, $event);
            } else {
                Yii::$app->session->addFlash('error', Yii::t('contact', 'Your message could not be sent.'));
                $this->controller->redirect([$this->viewFile, 'schema' => $schema]);

                $this->controller->trigger(MessageEvent::EVENT_SENT_MESSAGE_ERROR, $event);
            }
        }

        return $this->controller->render(
            $this->viewFile,
            [
                'model' => $contactForm,
                'schema' => $schema,
                'schemaData' => Json::decode($contactSchema->form_schema)
            ]
        );
    }
}
