<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Modèle pour la table "fredouil.reservation".
 */
class Reservation extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.reservation';
    }

    public function rules()
    {
        return [
            [['voyage', 'voyageur', 'nbplaceresa'], 'required'],
            [['voyage', 'voyageur', 'nbplaceresa'], 'integer'],
        ];
    }

    // --- RELATIONS ---

    public function getVoyage0()
    {
        return $this->hasOne(Voyage::class, ['id' => 'voyage']);
    }

    public function getVoyageur0() // Relation vers l'internaute qui voyage
    {
        return $this->hasOne(Internaute::class, ['id' => 'voyageur']);
    }

    // --- MÉTHODE SUJET [Source: 82] ---
    public static function getReservationsByVoyageId($voyageId)
    {
        return self::findAll(['voyage' => $voyageId]);
    }
}