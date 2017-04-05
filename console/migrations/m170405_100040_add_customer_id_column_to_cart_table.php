<?php

use yii\db\Migration;

/**
 * Handles adding customer_id to table `cart`.
 */
class m170405_100040_add_customer_id_column_to_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%cart}}', 'customer_id', $this->integer()->defaultValue(NULL));

        $this->createIndex('idx-cart-customer_id', '{{%cart}}', 'customer_id');

        $this->addForeignKey(
            'fk-cart-customer_id',
            '{{%cart}}',
            'customer_id',
            '{{%customer}}',
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
        $this->dropForeignKey('fk-cart-customer_id', '{{%cart}}');

        $this->dropIndex('idx-cart-customer_id', '{{%cart}}');

        $this->dropColumn('{{%cart}}', 'customer_id');
    }
}
