<?php

namespace dmstr\modules\contact;

/**
 * contact module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'crud/contact-log';
    public $frontendLayout = '//main';
    public $backendLayout = '@backend/views/layouts/box';
}
