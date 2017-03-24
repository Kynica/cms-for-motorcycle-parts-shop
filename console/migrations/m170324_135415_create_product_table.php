<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m170324_135415_create_product_table extends Migration
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

        $this->createTable('{{%product}}', [
            'id'             => $this->primaryKey(),
            'sku'            => $this->string(255),
            'name'           => $this->string(255)->notNull(),
            'price'          => $this->money(10,2)->notNull()->defaultValue(0.00),
            'old_price'      => $this->money(10,2)->notNull()->defaultValue(0.00),
            'purchase_price' => $this->money(10,2)->notNull()->defaultValue(0.00),
            'created_at'     => $this->dateTime()->notNull(),
            'updated_at'     => $this->dateTime()
        ], $tableOptions);

        $this->createIndex('idx-product-name',  '{{%product}}', 'name');
        $this->createIndex('idx-product-price', '{{%product}}', 'price');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
