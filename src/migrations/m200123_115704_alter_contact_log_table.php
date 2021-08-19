<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m200123_115704_alter_contact_log_table
 */
class m200123_115704_alter_contact_log_table extends Migration
{

    public function up()
    {
        $dateTimeType = $this->db->getDriverName() === 'mysql' ? 'DATETIME' : 'TIMESTAMP';

        $this->alterColumn('{{%dmstr_contact_log}}', 'json', 'TEXT NOT NULL');
        $this->alterColumn('{{%dmstr_contact_log}}', 'created_at', $dateTimeType);
        $this->addColumn('{{%dmstr_contact_log}}', 'updated_at', $dateTimeType);

        $schemaCluster = [];

        foreach ((new Query())->select(['name', 'id'])->from('{{%dmstr_contact_template}}')->all() as $schemaRow) {
            $schemaCluster[$schemaRow['name']] = $schemaRow['id'];
        }

        foreach ((new Query())->select(['id', 'schema'])->from('{{%dmstr_contact_log}}')->all() as $logRow) {
            $schemaId = -1;

            if (isset($schemaCluster[$logRow['schema']])) {
                $schemaId = $schemaCluster[$logRow['schema']];
            }

            $this->update('{{%dmstr_contact_log}}', ['schema' => $schemaId]);
        }

        $this->renameColumn('{{%dmstr_contact_log}}', 'schema', 'contact_template_id');

         if ($this->db->getDriverName() === 'pgsql') {
             $columnType = $this->integer() . ' USING contact_template_id::integer';
         } else {
             $columnType = 'INT(11) NULL';
         }

        $this->alterColumn('{{%dmstr_contact_log}}', 'contact_template_id', $columnType);

    }

    public function down()
    {
        echo "m200123_115704_alter_contact_log_table cannot be reverted.\n";
        return false;
    }
}
