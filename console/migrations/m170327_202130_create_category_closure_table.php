<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category_closure`.
 */
class m170327_202130_create_category_closure_table extends Migration
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

        $this->createTable('{{%category_closure}}', [
            'id'         => $this->primaryKey(),
            'ancestor'   => $this->integer()->notNull(),
            'descendant' => $this->integer()->notNull(),
            'depth'      => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('idx-category-closure-ancestor',   '{{%category_closure}}', 'ancestor');
        $this->createIndex('idx-category-closure-descendant', '{{%category_closure}}', 'descendant');

        $this->addForeignKey(
            'fk-category-closure-ancestor',
            '{{%category_closure}}',
            'ancestor',
            '{{%category}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-category-closure-descendant',
            '{{%category_closure}}',
            'descendant',
            '{{%category}}',
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
        $this->dropForeignKey('fk-category-closure-descendant', '{{%category_closure}}');
        $this->dropForeignKey('fk-category-closure-ancestor',   '{{%category_closure}}');

        $this->dropTable('{{%category_closure}}');
    }
}
