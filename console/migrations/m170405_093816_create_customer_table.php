<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer`.
 */
class m170405_093816_create_customer_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%customer}}', [
            'id'                   => $this->primaryKey(),
            'first_name'           => $this->string(45)->notNull(),
            'middle_name'          => $this->string(45)->defaultValue(NULL),
            'last_name'            => $this->string(45)->defaultValue(NULL),
            'phone_number'         => $this->string(45)->notNull(),
            'email'                => $this->string(255)->defaultValue(NULL),
            'password_hash'        => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->defaultValue(NULL),
            'created_at'           => $this->integer()->notNull()
        ]);

        $this->createIndex('idx-customer-phone_number', '{{%customer}}', 'phone_number');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%customer}}');
    }
}
