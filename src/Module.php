<?php

namespace dmstr\modules\contact;

/**
 * @property string $defaultRoute
 * @property string $frontendLayout
 * @property string $backendLayout
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
