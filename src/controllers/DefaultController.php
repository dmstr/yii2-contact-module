<?php

namespace dmstr\modules\contact\controllers;

use dmstr\modules\contact\models\ContactForm;
use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\models\ContactTemplate;
use dmstr\modules\contact\Module;
use yii;
use yii\helpers\Json;
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

        $contactSchema = ContactTemplate::findOne(['name' => $schema]);

        if ($contactSchema === null) {
            throw new NotFoundHttpException('Page not found.');
        }

        $contactForm = new ContactForm([
            'contact_template_id' => $contactSchema->id
        ]);


        if ($contactForm->load(Yii::$app->request->post()) && $contactForm->validate()) {

            $contactLog = new ContactLog([
                'contact_template_id' => $contactSchema->id,
                'json' => $contactForm->json,
            ]);

            if (!$contactLog->save()) {
                throw new HttpException(500, 'Your message could not be sent.');
            }
            Yii::$app->session->set(self::CONTACT_FORM_ID_KEY, $contactLog->id);

            if ($contactLog->sendMessage()) {
                $contactLog->sendConfirmMessage();
                Yii::$app->session->addFlash('success', Yii::t('contact', 'Your message was successfully sent.'));
            } else {
                Yii::$app->session->addFlash('error', Yii::t('contact', 'Your message could not be sent.'));
            }
            $this->redirect(['/contact/default/done', 'schema' => $schema]);
        }

        return $this->render(
            'index',
            [
                'model' => $contactForm,
                'schema' => $schema,
                'schemaData' => Json::decode($contactForm->schema)
            ]
        );
    }

    public function actionDone($schema)
    {
        $model = ContactLog::findOne(Yii::$app->session->get(self::CONTACT_FORM_ID_KEY));

        if ($model === null) {
            throw new ForbiddenHttpException(Yii::t('contact', 'You are not allowed to access this page directly.'));
        }

        Yii::$app->session->remove(self::CONTACT_FORM_ID_KEY);

        return $this->render(
            'done',
            [
                'schema' => $schema,
                'model' => Json::decode($model->json, false),
            ]
        );

    }

}
