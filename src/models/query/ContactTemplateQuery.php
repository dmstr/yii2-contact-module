<?php

namespace dmstr\modules\contact\models\query;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\contact\models\ContactTemplate]].
 *
 * @see \dmstr\modules\contact\models\ContactTemplate
 */
class ContactTemplateQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \dmstr\modules\contact\models\ContactTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\contact\models\ContactTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
