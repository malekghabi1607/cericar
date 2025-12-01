<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Modèle pour la table "fredouil.voyage".
 */
class Voyage extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.voyage';
    }

    public function rules()
    {
        return [
            [['conducteur', 'trajet', 'idtypev', 'idmarquev', 'tarif', 'nbplacedispo', 'nbbagage', 'heuredepart'], 'required'],
            [['conducteur', 'trajet', 'idtypev', 'idmarquev', 'nbplacedispo', 'nbbagage', 'heuredepart'], 'integer'],
            [['tarif'], 'number'],
            [['contraintes'], 'string', 'max' => 500],
        ];
    }

    // --- RELATIONS ---

    // Relation vers le Trajet (Attention : le nom 'trajet0' est utilisé dans la vue)
    public function getTrajet0()
    {
        return $this->hasOne(Trajet::class, ['id' => 'trajet']);
    }

    // Relation vers le Conducteur
    public function getConducteur0()
    {
        return $this->hasOne(Internaute::class, ['id' => 'conducteur']);
    }

    public function getTypevehicule()
    {
        return $this->hasOne(Typevehicule::class, ['id' => 'idtypev']);
    }

    public function getMarquevehicule()
    {
        return $this->hasOne(Marquevehicule::class, ['id' => 'idmarquev']);
    }

    // --- MÉTHODE SUJET [Source: 72] ---
    public static function getVoyagesByTrajetId($trajetId)
    {
        return self::findAll(['trajet' => $trajetId]);
    }
}