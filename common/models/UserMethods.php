<?php

namespace common\models;

/**
 * Class UserMethods
 * @package common\models
 *
 * @property Profile $profile
 */

class UserMethods extends User
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    public function getName()
    {
        return $this->profile->name;
    }
}