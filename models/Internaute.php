<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Modèle pour la table "fredouil.internaute".
 * @property int $id
 * @property string $pseudo
 * @property string $pass
 * @property string $nom
 * @property string $prenom
 * @property string $mail
 * @property string $photo
 * @property int|null $permis
 */
class Internaute extends ActiveRecord
{
    // Nom de la table dans la base PostgreSQL "fredouil"
    public static function tableName()
    {
        return 'fredouil.internaute';
    }

    // Règles de validation des données
    public function rules()
    {
        return [
            [['pseudo', 'pass', 'nom', 'prenom', 'mail'], 'required'],
            [['permis'], 'integer'],
            [['mail'], 'email'],
            [['pseudo', 'pass', 'nom', 'prenom', 'mail'], 'string', 'max' => 45],
            [['photo'], 'string', 'max' => 200],
        ];
    }

    // --- RELATIONS (Pour éviter les requêtes SQL manuelles) ---

    // Un internaute (conducteur) propose plusieurs voyages
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['conducteur' => 'id']);
    }

    // Un internaute (passager) a plusieurs réservations
    public function getReservations()
    {
        return $this->hasMany(Reservation::class, ['voyageur' => 'id']);
    }

    // --- MÉTHODES SPÉCIFIQUES DEMANDÉES PAR LE SUJET ---

    /**
     * Récupère un internaute selon son pseudo.
     * [Source: 83] "getUserByIdentifiant"
     */
    public static function getUserByIdentifiant($pseudo)
    {
        return self::findOne(['pseudo' => $pseudo]);
    }
}