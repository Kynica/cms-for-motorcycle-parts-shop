<?php

use yii\db\Migration;

/**
 * Handles the creation of table `supplier_product`.
 */
class m170331_120920_create_supplier_product_table extends Migration
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

        $this->createTable('{{%supplier_product}}', [
            'id'          => $this->primaryKey(),
            'supplier_id' => $this->integer()->notNull(),
            'product_id'  => $this->integer(),
            'sku'         => $this->string(25)->notNull(),
            'url'         => $this->string(255)
        ], $tableOptions);

        $this->createIndex('idx-supplier-product-supplier_id', '{{%supplier_product}}', 'supplier_id');
        $this->createIndex('idx-supplier-product-product_id',  '{{%supplier_product}}', 'product_id');

        $this->addForeignKey(
            'fk-supplier-product-supplier_id',
            '{{%supplier_product}}',
            'supplier_id',
            '{{%supplier}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-supplier-product-product_id',
            '{{%supplier_product}}',
            'product_id',
            '{{%product}}',
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
        $this->dropForeignKey('fk-supplier-product-product_id',  '{{%supplier_product}}');
        $this->dropForeignKey('fk-supplier-product-supplier_id', '{{%supplier_product}}');
        $this->dropTable('{{%supplier_product}}');
    }
}
