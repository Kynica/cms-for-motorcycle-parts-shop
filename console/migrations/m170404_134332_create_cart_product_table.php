<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart_product`.
 */
class m170404_134332_create_cart_product_table extends Migration
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

        $this->createTable('{{%cart_product}}', [
            'id'         => $this->primaryKey(),
            'cart_id'    => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity'   => $this->integer()->notNull()->defaultValue(1),
            'added_at'   => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('idx-cart-product-cart_id',    '{{%cart_product}}', 'cart_id');
        $this->createIndex('idx-cart-product-product_id', '{{%cart_product}}', 'product_id');

        $this->addForeignKey(
            'fk-cart-product-cart_id',
            '{{%cart_product}}',
            'cart_id',
            '{{%cart}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-cart-product-product_id',
            '{{%cart_product}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cart-product-product_id', '{{%cart_product}}');
        $this->dropForeignKey('fk-cart-product-cart_id',    '{{%cart_product}}');

        $this->dropTable('{{%cart_product}}');
    }
}
