<?php

use yii\db\Migration;
use common\models\User;

/**
 * Handles the creation of table `profile`.
 */
class m170323_142610_create_profile_table extends Migration
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

        $this->createTable('{{%profile}}', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer()->notNull(),
            'name'       => $this->string(45)->notNull(),
            'surname'    => $this->string(45)->notNull(),
            'patronymic' => $this->string(45),
        ], $tableOptions);

        $this->createIndex('idx-profile-user_id', '{{%profile}}', 'user_id');

        $this->addForeignKey(
            'fk-profile-user_id',
            '{{%profile}}',
            'user_id',
            '{{%user}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $user = new User([
            'username' => 'admin',
            'email'    => 'admin@google.com'
        ]);
        $user->setPassword('adminadmin');
        $user->generateAuthKey();
        if (! $user->save())
            throw new Exception('Can\'t add user');

        $this->insert('{{%profile}}', [
            'user_id' => $user->id,
            'name'    => 'admin',
            'surname' => 'admin',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-profile-user_id', '{{%profile}}');

        $this->dropTable('{{%profile}}');
        $this->dropTable('{{%user}}');
    }
}
