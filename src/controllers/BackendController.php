<?php

namespace dmstr\modules\contact\controllers;

class BackendController extends \yii\web\Controller
{
    public function init()
    {
        parent::init();
        $this->layout = $this->module->backendLayout;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
