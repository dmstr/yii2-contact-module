<?php

namespace dmstr\modules\contact;

use dmstr\web\traits\AccessBehaviorTrait;

/**
 * @property string $defaultRoute
 * @property string $frontendLayout
 * @property string $backendLayout
 * @property bool $sendHtmlMails
 */
class Module extends \yii\base\Module
{
    use AccessBehaviorTrait;

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'backend';
    public $frontendLayout = '//main';
    public $backendLayout = '@backend/views/layouts/box';
    public $exportFolder = '@backend/views/layouts/box';
    public $sendHtmlMails = false;

    public function beforeAction($action)
    {
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => 'Contact', 'url' => ['/'.$this->id]];

        return parent::beforeAction($action);
    }
}
