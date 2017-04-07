<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170404_133926_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%cart}}', [
            'id'         => $this->primaryKey(),
            'key'        => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'is_ordered' => "ENUM('yes', 'no') NOT NULL DEFAULT 'no'",
            'ordered_at' => $this->integer()->defaultValue(NULL)
        ], $tableOptions);

        $this->createIndex('idx-cart-key',        '{{%cart}}', 'key');
        $this->createIndex('idx-cart-is_ordered', '{{%cart}}', 'is_ordered');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%cart}}');
    }
}
