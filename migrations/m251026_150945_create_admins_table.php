<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admins}}`.
 */
class m251026_150945_create_admins_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admins}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string(100)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'name' => $this->string(100),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Insert default admin user (login: admin, password: admin)
        $this->insert('{{%admins}}', [
            'login' => 'admin',
            'password_hash' => password_hash('admin', PASSWORD_DEFAULT),
            'name' => 'Administrator',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admins}}');
    }
}
