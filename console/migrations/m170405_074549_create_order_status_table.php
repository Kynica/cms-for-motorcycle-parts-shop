<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_status`.
 */
class m170405_074549_create_order_status_table extends Migration
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

        $this->createTable('{{%order_status}}', [
            'id'       => $this->primaryKey(),
            'name'     => $this->string(25)->notNull(),
            'slug'     => $this->string(25)->notNull(),
            'priority' => $this->integer()->notNull()->defaultValue(1)
        ], $tableOptions);

        $this->batchInsert(
            '{{%order_status}}',
            ['name', 'slug', 'priority'],
            [
                ['Processing', 'processing', '1'],
                ['Refund',     'refund',     '2'],
                ['Shipped',    'shipped',    '3'],
                ['Completed',  'completed',  '4'],
                ['Canceled',   'canceled',   '5'],
                ['Failed',     'failed',     '6'],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_status}}');
    }
}
