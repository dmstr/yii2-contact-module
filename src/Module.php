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
    public $controllerNamespace = 'dmstr\modules\contact\controllers';

    public $frontendLayout = '//main';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
