<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.marquevehicule".
 *
 * @property int $id
 * @property string $marquev
 *
 * Relations :
 * @property Voyage[] $voyages
 */
class Marquevehicule extends ActiveRecord
{
    /**
     * Nom de la table PostgreSQL (avec schéma)
     */
    public static function tableName()
    {
        return 'fredouil.marquevehicule';
    }

    /**
     * Règles de validation
     */
    public function rules()
    {
        return [
            [['marquev'], 'required'],
            [['marquev'], 'string', 'max' => 25],
        ];
    }

    /**
     * Labels des attributs
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'marquev' => 'Marque du véhicule',
        ];
    }

    /**
     * Relation :
     * Une marque peut être utilisée dans plusieurs voyages
     */
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['idmarquev' => 'id']);
    }
}