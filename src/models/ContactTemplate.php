<?php

namespace dmstr\modules\contact\models;

use dmstr\activeRecordPermissions\ActiveRecordAccessTrait;
use \dmstr\modules\contact\models\base\ContactTemplate as BaseContactTemplate;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "app_dmstr_contact_template".
 */
class ContactTemplate extends BaseContactTemplate
{
    use ActiveRecordAccessTrait {
        find as traitFind;
}

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

    /**
     * @return array
     */
    public static function optsCaptcha()
    {
        return [
            0 => \Yii::t('contact', 'No'),
            1 => \Yii::t('contact', 'Yes'),
        ];
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules['default-access_domain'] = [
            'access_domain',
            'default',
            'value' => self::getDefaultAccessDomain()
        ];
        $rules['default-access_read'] = [
            'access_read',
            'default',
            'value' => self::$_all
        ];
        $rules['default-access_update-delete'] = [
            [
                'access_update',
                'access_delete',
            ],
            'default',
            'value' => self::getDefaultAccessUpdateDelete()
        ];
        return $rules;
    }

    public static function find()
    {
        self::$enableRecursiveRoles = true;
        return self::traitFind();
    }
}
