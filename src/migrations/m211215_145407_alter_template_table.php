<?php

use yii\db\Migration;

/**
 * Class m211215_145407_alter_template_table
 */
class m211215_145407_alter_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dmstr_contact_template}}', 'access_owner', 'VARCHAR(11) NULL');
        $this->addColumn('{{%dmstr_contact_template}}', 'access_domain', 'VARCHAR(8) NULL');
        $this->addColumn('{{%dmstr_contact_template}}', 'access_read', 'VARCHAR(255) NULL');
        $this->addColumn('{{%dmstr_contact_template}}', 'access_update', 'VARCHAR(255) NULL');
        $this->addColumn('{{%dmstr_contact_template}}', 'access_delete', 'VARCHAR(255) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%dmstr_contact_template}}', 'access_owner');
        $this->dropColumn('{{%dmstr_contact_template}}', 'access_domain');
        $this->dropColumn('{{%dmstr_contact_template}}', 'access_read');
        $this->dropColumn('{{%dmstr_contact_template}}', 'access_update');
        $this->dropColumn('{{%dmstr_contact_template}}', 'access_delete');
    }

}
