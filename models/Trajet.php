<?php

namespace app\models;

use yii\db\ActiveRecord;

class Trajet extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.trajet';
    }

    public function rules()
    {
        return [
            [['depart', 'arrivee', 'distance'], 'required'],
            [['distance'], 'integer'],
            [['depart', 'arrivee'], 'string', 'max' => 25],
        ];
    }
    
    // --- MÉTHODE SUJET [Source: 71] ---
    public static function getTrajet($depart, $arrivee)
    {
        return self::findOne(['depart' => $depart, 'arrivee' => $arrivee]);
    }
}