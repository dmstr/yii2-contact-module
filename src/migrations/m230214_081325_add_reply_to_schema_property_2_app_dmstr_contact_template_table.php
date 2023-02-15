<?php

use yii\db\Migration;

class m230214_081325_add_reply_to_schema_property_2_app_dmstr_contact_template_table extends Migration
{
    public function safeUp()
    {

        $this->addColumn('{{%dmstr_contact_template}}','reply_to_schema_property', $this->string()->null()->defaultValue(null)->after('return_path'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%dmstr_contact_template}}','reply_to_schema_property');
    }
}
