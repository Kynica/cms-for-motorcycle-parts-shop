<?php

use yii\db\Migration;

/**
 * Handles adding order_id to table `cart`.
 */
class m170405_084632_add_order_id_column_to_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%cart}}', 'order_id', $this->integer());

        $this->createIndex('idx-cart-order_id', '{{%cart}}', 'order_id');

        $this->addForeignKey(
            'fk-cart-order_id',
            '{{%cart}}',
            'order_id',
            '{{%order}}',
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
        $this->dropForeignKey('fk-cart-order_id', '{{%cart}}');

        $this->dropIndex('idx-cart-order_id', '{{%cart}}');

        $this->dropColumn('{{%cart}}', 'order_id');
    }
}
