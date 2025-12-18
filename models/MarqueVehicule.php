<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Modèle ActiveRecord pour la table "fredouil.marquevehicule".
 *
 * @property int $id
 * @property string $marquev
 *
 * Relations :
 * @property Voyage[] $voyages  Liste des voyages utilisant cette marque
 */
class Marquevehicule extends ActiveRecord
{
    /**
     * Nom de la table PostgreSQL (avec son schéma)
     */
    public static function tableName()
    {
        return 'fredouil.marquevehicule';
    }

    /**
     * Règles de validation du modèle
     */
    public function rules()
    {
        return [
            [['marquev'], 'required'],
            [['marquev'], 'string', 'max' => 25],
        ];
    }

    /**
     * Labels des attributs utilisés dans les formulaires
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'marquev' => 'Marque du véhicule',
        ];
    }

    /**
     * Relation AR :
     * Une marque de véhicule peut être liée à plusieurs voyages
     */
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['idmarquev' => 'id']);
    }
}
