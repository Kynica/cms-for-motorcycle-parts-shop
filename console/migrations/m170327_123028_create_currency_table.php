<?php

use yii\db\Migration;

/**
 * Handles the creation of table `currency`.
 */
class m170327_123028_create_currency_table extends Migration
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

        $this->createTable('{{%currency}}', [
            'id'     => $this->primaryKey(),
            'code'   => $this->string(25)->notNull(),
            'name'   => $this->string(25)->notNull(),
            'symbol' => $this->string(25)->notNull(),
            'rate'   => $this->money(10,2)->notNull()->defaultValue(1.00)
        ], $tableOptions);

        $this->createIndex('idx-currency-code', '{{%currency}}', 'code');

        $this->insert(
            '{{%currency}}',
            [
                'code'   => 'USD',
                'name'   => 'US Dollar',
                'symbol' => '$',
                'rate'   => '1.00'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency}}');
    }
}
