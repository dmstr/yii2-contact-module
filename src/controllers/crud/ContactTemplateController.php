<?php
/**
 * /app/runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace dmstr\modules\contact\controllers\crud;

/**
 * This is the class for controller "ContactTemplateController".
 */
class ContactTemplateController extends \dmstr\modules\contact\controllers\crud\base\ContactTemplateController
{
    public function init()
    {
        parent::init();
        $this->layout = $this->module->backendLayout;
    }
}
