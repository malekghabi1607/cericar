<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Voyage;
use app\models\Internaute;

class Reservation extends ActiveRecord
{
    public static function tableName()
    {
        return 'fredouil.reservation';
    }

    /**
     * Relation : 1 réservation → 1 voyage
     * reservation.voyage → voyage.id
     */
    public function getVoyage()
    {
        return $this->hasOne(Voyage::class, ['id' => 'voyage']);
    }

    // Same relation, but named to avoid conflict with the "voyage" column.
    public function getVoyageInfo()
    {
        return $this->hasOne(Voyage::class, ['id' => 'voyage']);
    }

    /**
     * Relation : 1 réservation → 1 internaute (voyageur)
     * reservation.voyageur → internaute.id
     */
    public function getVoyageur()
    {
        return $this->hasOne(Internaute::class, ['id' => 'voyageur']);
    }

    /**
     * Règles de validation
     */
    public function rules()
    {
        return [
            [['voyage', 'voyageur', 'nbplaceresa'], 'required'],
            [['voyage', 'voyageur', 'nbplaceresa'], 'integer'],
        ];
    }

    /**
     * Retourne toutes les réservations pour un voyage donné
     */
    public static function getReservationsByVoyageId($voyageId)
    {
        return self::findAll(['voyage' => $voyageId]);
    }
}
