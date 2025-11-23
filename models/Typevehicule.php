<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.typevehicule".
 *
 * @property int $id
 * @property string $typev
 *
 * Relation :
 * @property Voyage[] $voyages
 */
class Typevehicule extends ActiveRecord
{
    /**
     * Nom de la table PostgreSQL (avec schéma)
     */
    public static function tableName()
    {
        return 'fredouil.typevehicule';
    }

    /**
     * Règles de validation
     */
    public function rules()
    {
        return [
            [['typev'], 'required'],
            [['typev'], 'string', 'max' => 25],
        ];
    }

    /**
     * Labels des attributs
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'typev' => 'Type de véhicule',
        ];
    }

    /**
     * Relation :
     * Un type de véhicule appartient à plusieurs voyages
     */
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['idtypev' => 'id']);
    }
}