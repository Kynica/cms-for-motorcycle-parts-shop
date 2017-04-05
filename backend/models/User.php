<?php

namespace backend\models;

use common\models\User AS U;
use common\models\Profile;

class User extends U
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }
}