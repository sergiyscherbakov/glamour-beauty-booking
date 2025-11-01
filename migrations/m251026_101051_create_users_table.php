<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m251026_101051_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'email' => $this->string(100)->notNull()->unique(),
            'phone' => $this->string(20)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-users-email', '{{%users}}', 'email');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
