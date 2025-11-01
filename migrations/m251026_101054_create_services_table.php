<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%services}}`.
 */
class m251026_101054_create_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%services}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'description' => $this->text(),
            'duration' => $this->integer()->notNull()->comment('Duration in minutes'),
            'price' => $this->decimal(10, 2)->notNull(),
            'is_active' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%services}}');
    }
}
