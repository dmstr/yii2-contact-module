<?php

namespace dmstr\modules\contact\controllers;

use dmstr\modules\contact\controllers\actions\SubmitAction;
use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\Module;
use yii;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * @property mixed $settings
 * @property Module $module
 */
class DefaultController extends Controller
{
    const CONTACT_FORM_ID_KEY = 'contact:formId';

    public function init()
    {
        parent::init();
        $this->layout = $this->module->frontendLayout;
    }

    public function actionDone($schema)
    {
        $model = ContactLog::findOne(Yii::$app->session->get(self::CONTACT_FORM_ID_KEY));
        Yii::$app->session->remove(self::CONTACT_FORM_ID_KEY);

        if ($model === null) {
            return $this->render('done-expired');
        }

        return $this->render(
            'done',
            [
                'schema' => $schema,
                'model' => Json::decode($model->json, false),
            ]
        );

    }

    /**
     * @param string $schema
     *
     * @return string
     */
    public function actionForm($schema)
    {
        return $this->render('form', [
            'schema' => $schema
        ]);
    }

    public function actions()
    {
        $testLimit = Yii::$app->settings->getOrSet('testLimit', 100, 'captcha', 'integer');
        $width = Yii::$app->settings->getOrSet('width', 140, 'captcha', 'integer');
        $height = Yii::$app->settings->getOrSet('height', 75, 'captcha', 'integer');
        $offset = Yii::$app->settings->getOrSet('offset', -1, 'captcha', 'integer');
        $backColor = Yii::$app->settings->getOrSet('backColor', '0x333333', 'captcha', 'string');
        $foreColor = Yii::$app->settings->getOrSet('foreColor', '0xFFFFFF', 'captcha', 'string');
        $actions = parent::actions();
        $actions['captcha'] = [
            'class' => CaptchaAction::class,
            'testLimit' => $testLimit,
            'width' => $width,
            'height' => $height,
            'offset' => $offset,
            'backColor' => hexdec($backColor),
            'foreColor' => hexdec($foreColor)
        ];
        $actions['index'] = [
            'class' => SubmitAction::class
        ];
        $actions['form'] = [
            'class' => SubmitAction::class,
            'viewFile' => 'form'
        ];
        return $actions;
    }

}
