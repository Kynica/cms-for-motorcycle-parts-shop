<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category_product_margin`.
 */
class m170402_082655_create_category_product_margin_table extends Migration
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

        $this->createTable('{{%category_product_margin}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'currency_id' => $this->integer()->notNull(),
            'margin_type' => "ENUM('sum', 'percent') NOT NULL DEFAULT 'percent'",
            'margin'      => $this->money(10,2)->notNull()->defaultValue(0.00)
        ], $tableOptions);

        $this->createIndex('idx-category-product-margin-category_id', '{{%category_product_margin}}', 'category_id');
        $this->createIndex('idx-category-product-margin-currency_id', '{{%category_product_margin}}', 'currency_id');

        $this->addForeignKey(
            'fk-category-product-margin-category_id',
            '{{%category_product_margin}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-category-product-margin-currency_id',
            '{{%category_product_margin}}',
            'currency_id',
            '{{%currency}}',
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
        $this->dropForeignKey('fk-category-product-margin-currency_id', '{{%category_product_margin}}');
        $this->dropForeignKey('fk-category-product-margin-category_id', '{{%category_product_margin}}');

        $this->dropTable('{{%category_product_margin}}');
    }
}
