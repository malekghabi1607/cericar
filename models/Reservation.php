<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.reservation".
 *
 * Attributs base de données :
 * @property int $id
 * @property int $voyage
 * @property int $voyageur
 * @property int $nbplaceresa
 *
 * Attributs UML ajoutés (non persistants) :
 * @property string|null $dateReservation
 * @property float|null $montant
 */
class Reservation extends ActiveRecord
{
    /**
     * Table PostgreSQL
     */
    public static function tableName()
    {
        return 'fredouil.reservation';
    }

    /**
     * Attributs UML (non stockés)
     */
    public $dateReservation;
    public $montant;

    /**
     * Règles de validation
     */
    public function rules()
    {
        return [
            // Champs obligatoires BD
            [['voyage', 'voyageur', 'nbplaceresa'], 'required'],

            // Types numériques
            [['voyage', 'voyageur', 'nbplaceresa'], 'integer'],

            // Attributs UML
            [['dateReservation'], 'safe'],
            [['montant'], 'number'],
        ];
    }

    /**
     * Labels
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voyage' => 'Voyage',
            'voyageur' => 'Voyageur',
            'nbplaceresa' => 'Nombre de places réservées',

            // UML
            'dateReservation' => 'Date de réservation',
            'montant' => 'Montant total (€)',
        ];
    }

    /**
     * Relations :
     * Reservation → Voyage
     * Reservation → Internaute (voyageur)
     */

    public function getVoyage0()
    {
        return $this->hasOne(Voyage::class, ['id' => 'voyage']);
    }

    public function getVoyageur0()
    {
        return $this->hasOne(Internaute::class, ['id' => 'voyageur']);
    }
}