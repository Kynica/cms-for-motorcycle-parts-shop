<?php

use yii\db\Migration;

/**
 * Handles adding seller_id to table `cart`.
 */
class m170405_142702_add_seller_id_column_to_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%cart}}', 'seller_id', $this->integer()->defaultValue(NULL));

        $this->createIndex('idx-cart-seller_id', '{{%cart}}', 'seller_id');

        $this->addForeignKey(
            'fk-cart-seller_id',
            '{{%cart}}',
            'seller_id',
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
        $this->dropForeignKey('fk-cart-seller_id', '{{%cart}}');

        $this->dropIndex('idx-cart-seller_id', '{{%cart}}');

        $this->dropColumn('{{%cart}}', 'seller_id');
    }
}
