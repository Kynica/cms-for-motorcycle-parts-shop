<?php

use yii\db\Migration;

/**
 * Handles adding category_id to table `product`.
 */
class m170329_081617_add_category_id_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'category_id', $this->integer());

        $this->createIndex('idx-product-category_id', '{{%product}}', 'category_id');

        $this->addForeignKey(
            'fk-product-category_id',
            '{{%product}}',
            'category_id',
            '{{%category}}',
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
        $this->dropForeignKey('fk-product-category_id', '{{%product}}');

        $this->dropIndex('idx-product-category_id', '{{%product}}');

        $this->dropColumn('{{%product}}', 'category_id');
    }
}
