<?php

use yii\db\Migration;

class m251026_112445_add_category_and_image_to_services extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%services}}', 'category', $this->string(50)->after('description'));
        $this->addColumn('{{%services}}', 'image_url', $this->string(500)->after('category'));
        $this->createIndex('idx-services-category', '{{%services}}', 'category');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-services-category', '{{%services}}');
        $this->dropColumn('{{%services}}', 'image_url');
        $this->dropColumn('{{%services}}', 'category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251026_112445_add_category_and_image_to_services cannot be reverted.\n";

        return false;
    }
    */
}
