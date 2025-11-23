<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.trajet".
 *
 * @property int $id
 * @property string $depart
 * @property string $arrivee
 * @property int $distance
 *
 * Attribut UML non persistant :
 * @property int|null $dureeEstimee
 */
class Trajet extends ActiveRecord
{
    /**
     * Nom de la table PostgreSQL (avec le schéma)
     */
    public static function tableName()
    {
        return 'fredouil.trajet';
    }

    /**
     * Attribut UML ajouté : non stocké en base
     */
    public $dureeEstimee;

    /**
     * Règles de validation
     */
    public function rules()
    {
        return [
            // Champs obligatoires provenant de la BD
            [['depart', 'arrivee', 'distance'], 'required'],

            // Types numériques
            [['distance'], 'integer'],

            // Longueurs max des chaînes
            [['depart', 'arrivee'], 'string', 'max' => 25],

            // Attribut UML (optionnel)
            [['dureeEstimee'], 'safe'],
        ];
    }

    /**
     * Labels des attributs
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'depart' => 'Ville de départ',
            'arrivee' => 'Ville d\'arrivée',
            'distance' => 'Distance (km)',

            // Attribut UML
            'dureeEstimee' => 'Durée estimée (minutes)',
        ];
    }

    /**
     * Relation : un trajet est utilisé par plusieurs voyages
     */
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['trajet' => 'id']);
    }

    /**
     * Méthode UML : calcul de la durée estimée
     * On suppose une vitesse moyenne de 60 km/h (1 km = 1 min)
     */
    public function calculerDureeEstimee()
    {
        if ($this->distance) {
            return $this->distance; // 1 km = 1 minute
        }
        return null;
    }
}