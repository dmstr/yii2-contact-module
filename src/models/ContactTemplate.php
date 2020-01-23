<?php

namespace dmstr\modules\contact\models;

use Yii;
use \dmstr\modules\contact\models\base\ContactTemplate as BaseContactTemplate;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "app_dmstr_contact_template".
 */
class ContactTemplate extends BaseContactTemplate
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_contact_template}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()')
        ];
        return $behaviors;
    }
}
