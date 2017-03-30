<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m170327_200419_create_category_table extends Migration
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

        $this->createTable('{{%category}}', [
            'id'        => $this->primaryKey(),
            'name'      => $this->string(45)->notNull(),
            'parent_id' => $this->integer()
        ], $tableOptions);

        $this->createIndex('idx-category-parent_id', '{{%category}}', 'parent_id');

        $this->addForeignKey(
            'fk-category-parent_id',
            '{{%category}}',
            'parent_id',
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
        $this->dropForeignKey('fk-category-parent_id', '{{%category}}');

        $this->dropTable('{{%category}}');
    }
}
