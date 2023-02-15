<?php

use yii\db\Migration;

class m230214_071325_add_return_path_2_app_dmstr_contact_template_table extends Migration
{
    public function safeUp()
    {

        $this->addColumn('app_dmstr_contact_template','return_path', $this->string()->null()->defaultValue(null)->after('to_email'));
    }

    public function safeDown()
    {
        $this->dropColumn('app_dmstr_contact_template','return_path');
    }
}
