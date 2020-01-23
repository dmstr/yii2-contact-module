<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace dmstr\modules\contact\models\base;

use Yii;

/**
 * This is the base-model class for table "app_dmstr_contact_log".
 *
 * @property integer $id
 * @property integer $contact_template_id
 * @property string $json
 * @property string $created_at
 * @property string $updated_at
 * @property string $aliasModel
 */
abstract class ContactLog extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_dmstr_contact_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_template_id', 'json'], 'required'],
            [['contact_template_id'], 'integer'],
            [['json'], 'string'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'contact_template_id' => Yii::t('models', 'Contact Template ID'),
            'json' => Yii::t('models', 'Json'),
            'created_at' => Yii::t('models', 'Created At'),
            'updated_at' => Yii::t('models', 'Updated At'),
        ];
    }


    
    /**
     * @inheritdoc
     * @return \dmstr\modules\contact\models\query\ContactLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\modules\contact\models\query\ContactLogQuery(get_called_class());
    }


}
