<?php

use yii\db\Expression;
use yii\db\Migration;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class m200123_081049_convert_settings_to_contact_template
 */
class m200123_081049_convert_settings_to_contact_template extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $schemaRows = (new Query())
            ->select(['name' => new Expression('SUBSTR(`key`, 1, LOCATE(".", `key`) - 1)')])
            ->from('{{%settings}}')
            ->where(['section' => 'contact'])
            ->groupBy('name')
            ->all();

        $schemaNames = ArrayHelper::getColumn($schemaRows, 'name');


        foreach ($schemaNames as $schemaName) {
            $settingRows = (new Query())
                ->select([
                    'value',
                    'identifier' => new Expression('SUBSTR(`key`, LOCATE(".", `key`) + 1)'),
                    'id'
                ])
                ->from('{{%settings}}')
                ->where(['section' => 'contact'])
                ->andWhere("`key` LIKE '{$schemaName}.%'")
                ->all();

            $columns = [
                'name' => $schemaName,
                'created_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()')
            ];

            $settingsIds = [];

            foreach ($settingRows as $settingRow) {
                $value = $settingRow['value'];
                switch ($settingRow['identifier']) {
                    case 'schema':
                        $columns['form_schema'] = $value;
                        break;
                    case 'toEmail':
                        $columns['to_email'] = $value;
                        break;
                    case 'fromEmail':
                        $columns['from_email'] = $value;
                        break;
                    case 'subject':
                        $columns['email_subject'] = $value;
                        break;
                    case 'confirmMail':
                        $columns['send_confirm_email'] = 1;
                        $columns['confirm_email_text'] = $value;
                        break;
                }
                $settingsIds[] = $settingRow['id'];
            }

            $this->insert('{{%dmstr_contact_template}}', $columns);
            $this->delete('{{%settings}}', ['id' => $settingsIds]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200123_081049_convert_settings_to_contact_template cannot be reverted.\n";

        return false;
    }
}
