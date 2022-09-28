<?php

use yii\db\Migration;

/**
 * Class m220927_121620_alter_contact_log_charset
 */
class m220927_121620_alter_contact_log_charset extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('ALTER TABLE {{%dmstr_contact_log}} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        $this->execute('ALTER TABLE {{%dmstr_contact_log}} CHANGE [[json]] [[json]] TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;');

        $this->execute('ALTER TABLE {{%dmstr_contact_template}} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        $this->execute('ALTER TABLE {{%dmstr_contact_template}} CHANGE [[name]] [[name]] VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;');
        $this->execute('ALTER TABLE {{%dmstr_contact_template}} CHANGE [[from_email]] [[from_email]] VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;');
        $this->execute('ALTER TABLE {{%dmstr_contact_template}} CHANGE [[to_email]] [[to_email]] VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;');
        $this->execute('ALTER TABLE {{%dmstr_contact_template}} CHANGE [[reply_to_email]] [[reply_to_email]] VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;');
        $this->execute('ALTER TABLE {{%dmstr_contact_template}} CHANGE [[email_subject]] [[email_subject]] VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;');
        $this->execute('ALTER TABLE {{%dmstr_contact_template}} CHANGE [[form_schema]] [[form_schema]] MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220927_121620_alter_contact_log_charset cannot be reverted.\n";
        return false;
    }
}
