<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_image`.
 */
class m170329_102618_create_product_image_table extends Migration
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

        $this->createTable('{{%product_image}}', [
            'id'         => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'path'       => $this->string(255)->notNull(),
            'sort'       => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('idx-product-image-product_id', '{{%product_image}}', 'product_id');

        $this->addForeignKey(
            'fk-product-image-product_id',
            '{{%product_image}}',
            'product_id',
            '{{%product}}',
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
        $this->dropForeignKey('fk-product-image-product_id', '{{%product_image}}');
        $this->dropTable('{{%product_image}}');
    }
}
