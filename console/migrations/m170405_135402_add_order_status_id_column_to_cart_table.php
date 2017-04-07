<?php

use yii\db\Migration;

/**
 * Handles adding order_status_id to table `cart`.
 */
class m170405_135402_add_order_status_id_column_to_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%cart}}', 'order_status_id', $this->integer()->defaultValue(NULL));

        $this->createIndex('idx-cart-order-status_id', '{{%cart}}', 'order_status_id');

        $this->addForeignKey(
            'fk-cart-order-status_id',
            '{{%cart}}',
            'order_status_id',
            '{{%order_status}}',
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
        $this->dropForeignKey('fk-cart-order-status_id', '{{%cart}}');

        $this->dropIndex('idx-cart-order-status_id', '{{%cart}}');

        $this->dropColumn('{{%cart}}', 'order_status_id');
    }
}
