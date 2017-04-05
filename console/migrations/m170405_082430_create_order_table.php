<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170405_082430_create_order_table extends Migration
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

        $this->createTable('{{%order}}', [
            'id'         => $this->primaryKey(),
            'status_id'  => $this->integer(),
            'cart_id'    => $this->integer()->notNull(),
            'user_id'    => $this->integer(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-order-status_id', '{{%order}}', 'status_id');
        $this->createIndex('idx-order-cart_id',   '{{%order}}', 'cart_id');
        $this->createIndex('idx-order-user_id',   '{{%order}}', 'user_id');

        $this->addForeignKey(
            'fk-order-status_id',
            '{{%order}}',
            'status_id',
            '{{%order_status}}',
            'id',
            'SET NULL',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-order-cart_id',
            '{{%order}}',
            'cart_id',
            '{{%cart}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-order-user_id',
            '{{%order}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'NO ACTION'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-order-user_id',   '{{%order}}');
        $this->dropForeignKey('fk-order-cart_id',   '{{%order}}');
        $this->dropForeignKey('fk-order-status_id', '{{%order}}');

        $this->dropTable('{{%order}}');
    }
}
