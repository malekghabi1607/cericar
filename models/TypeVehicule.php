<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.typevehicule".
 *
 * @property int $id
 * @property string $typev
 *
 * @property Voyage[] $voyages
 */
class TypeVehicule extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.typevehicule';
    }

    public function rules()
    {
        return [
            [['typev'], 'required'],
            [['typev'], 'string', 'max' => 25],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'typev' => 'Type de véhicule',
        ];
    }

    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['idtypev' => 'id']);
    }
}
