<?php

namespace dmstr\modules\contact\models\query;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\contact\models\ContactLog]].
 *
 * @see \dmstr\modules\contact\models\ContactLog
 */
class ContactLogQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \dmstr\modules\contact\models\ContactLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\contact\models\ContactLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
