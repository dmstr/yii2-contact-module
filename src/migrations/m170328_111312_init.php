<?php

use yii\db\Migration;

class m170328_111312_init extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%dmstr_contact_log}}',
            [
                'id' => $this->primaryKey(),
                'schema' => $this->string(64),
                'json' => $this->text(),
                'created_at' => $this->timestamp(),
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%dmstr_contact_log}}');
    }
}
