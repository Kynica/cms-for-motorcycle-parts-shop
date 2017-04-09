<?php

namespace backend\models;

use yii\base\Model;
use common\models\User;
use yii\db\ActiveQuery;

class UserForm extends Model
{
    public $id;
    public $username = '';
    public $password = '';
    public $email    = '';

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            ['username', 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [
                'username', 'unique', 'targetClass' => '\common\models\User', 'targetAttribute' => 'username',
                'message' => 'This username has already been taken.',
                'on' => self::SCENARIO_CREATE
            ],
            [
                'username', 'unique', 'targetClass' => User::className(),
                'filter' => function ($query) {
                    /** @var ActiveQuery $query */
                    $query->andWhere([
                        'not', ['id' => $this->id]
                    ]);
                },
                'message' => 'This username has already been taken.',
                'on' => self::SCENARIO_UPDATE
            ],
            ['username', 'string', 'min' => 2, 'max' => 255, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            ['email', 'trim', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            ['email', 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            ['email', 'email', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            ['email', 'string', 'max' => 255, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [
                'email', 'unique', 'targetClass' => '\common\models\User', 'targetAttribute' => 'email',
                'message' => 'This email address has already been taken.', 'on' => self::SCENARIO_CREATE
            ],
            [
                'email', 'unique', 'targetClass' => User::className(),
                'filter' => function ($query) {
                    /** @var ActiveQuery $query */
                    $query->andWhere([
                        'not', ['id' => $this->id]
                    ]);
                },
                'message' => 'This email address has already been taken.',
                'on' => self::SCENARIO_UPDATE
            ],

            ['password', 'required', 'on' => [self::SCENARIO_CREATE]],
            ['password', 'string', 'min' => 6, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]]
        ];
    }

    public function create()
    {
        if ($this->validate()) {
            $user = new User([
                'username' => $this->username,
                'email'    => $this->email,
            ]);

            $user->setPassword($this->password);
            $user->generateAuthKey();

            return $user->save() ? $user : null;
        }

        return null;
    }

    public function update(User $user) {
        $this->id = $user->id;
        if ($this->validate()) {
            if ($this->username != '') {
                $user->username = $this->username;
                $user->save();
            }

            if ($this->password != '') {
                $user->setPassword($this->password);
                $user->generateAuthKey();
                $user->save();
            }

            if ($this->email != '') {
                $user->email = $this->email;
                $user->save();
            }
        }
    }
}