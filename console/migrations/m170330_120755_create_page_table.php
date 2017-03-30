<?php

use yii\db\Migration;

/**
 * Handles the creation of table `page`.
 */
class m170330_120755_create_page_table extends Migration
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

        $this->createTable('{{%page}}', [
            'id'         => $this->primaryKey(),
            'url'        => $this->string(500)->notNull(),
            'controller' => $this->string(45)->notNull(),
            'entity_id'  => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('idx-page-url', '{{%page}}', 'url');
        $this->createIndex(
            'idx-page-controller-entity_id',
            '{{%page}}',
            [
                'controller',
                'entity_id'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%page}}');
    }
}
