<?php

use yii\db\Migration;

/**
 * Handles adding currency_id to table `product`.
 */
class m170327_124354_add_currency_id_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'currency_id', $this->integer());

        $this->createIndex('idx-product-currency_id', '{{%product}}', 'currency_id');
        $this->addForeignKey(
            'fk-product-currency_id',
            '{{%product}}',
            'currency_id',
            '{{%currency}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-product-currency_id', '{{%product}}');

        $this->dropColumn('{{%product}}', 'currency_id');
    }
}
