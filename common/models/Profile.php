<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $name
 * @property string  $surname
 * @property string  $patronymic
 *
 * @property User $user
 */
class Profile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'surname'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 45],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'User ID',
            'name'       => 'Name',
            'surname'    => 'Surname',
            'patronymic' => 'Patronymic',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getLogin()
    {
        return $this->user->username;
    }

    public function beforeDelete()
    {
        if (Yii::$app->user->getId() == $this->user->id)
            return false;

        return true;
    }

    public function afterDelete()
    {
        if (! parent::afterDelete())
            return false;

        if (! $this->user->delete())
            throw new Exception('Some error. Can\'t delete User');

        return true;
    }
}
