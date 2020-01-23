<?php

use yii\db\Migration;

/**
 * Class m200123_080458_add_contact_template_table
 */
class m200123_080458_add_contact_template_table extends Migration
{

    public function up()
    {
        $this->createTable(
            '{{%dmstr_contact_template}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->unique(),
                'from_email' => $this->string()->notNull(),
                'reply_to_email' => $this->string(),
                'to_email' => $this->string()->notNull(),
                'email_subject' => $this->string(),
                'form_schema' => $this->text(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime()
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%dmstr_contact_template}}');
    }
}
