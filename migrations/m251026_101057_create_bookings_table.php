<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bookings}}`.
 */
class m251026_101057_create_bookings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bookings}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'service_id' => $this->integer()->notNull(),
            'booking_date' => $this->date()->notNull(),
            'booking_time' => $this->time()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('pending'),
            'notes' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-bookings-user_id', '{{%bookings}}', 'user_id');
        $this->createIndex('idx-bookings-service_id', '{{%bookings}}', 'service_id');
        $this->createIndex('idx-bookings-status', '{{%bookings}}', 'status');
        $this->createIndex('idx-bookings-date', '{{%bookings}}', 'booking_date');

        $this->addForeignKey(
            'fk-bookings-user_id',
            '{{%bookings}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-bookings-service_id',
            '{{%bookings}}',
            'service_id',
            '{{%services}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bookings}}');
    }
}
