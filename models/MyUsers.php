<?php
namespace app\models;

use yii\db\ActiveRecord;

class MyUsers extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.my_users';
    }
}