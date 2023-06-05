<?php

use yii\db\Migration;

class m201012_141325_alter_app_dmstr_contact_template_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%dmstr_contact_template}}','captcha', $this->boolean()->notNull()->defaultValue(null)->after('email_subject'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%dmstr_contact_template}}','captcha');
    }
}
