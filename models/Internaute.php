<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.internaute".
 *
 * @property int $id
 * @property string $pseudo
 * @property string $pass
 * @property string $nom
 * @property string $prenom
 * @property string $mail
 * @property string $photo
 * @property int|null $permis
 *
 * Attributs supplémentaires non persistants (UML)
 * @property string|null $telephone
 * @property string|null $adresse
 * @property string|null $dateInscription
 */
class Internaute extends ActiveRecord
{
    /**
     * Nom de la table PostgreSQL (avec le schéma)
     */
    public static function tableName()
    {
        return 'fredouil.internaute';
    }

    /**
     * Attributs supplémentaires du modèle (non dans la BD)
     * Ils sont déclarés ici pour les utiliser dans les vues / formulaires.
     */
    public $telephone;
    public $adresse;
    public $dateInscription;

    /**
     * Règles de validation
     */
    public function rules()
    {
        return [
            // Champs obligatoires venant de la BD
            [['pseudo', 'pass', 'nom', 'prenom', 'mail'], 'required'],

            // Types numériques
            [['permis'], 'integer'],

            // Types chaînes avec longueur max
            [['pseudo', 'pass', 'nom', 'prenom', 'mail'], 'string', 'max' => 45],
            [['photo'], 'string', 'max' => 200],

            // Email
            [['mail'], 'email'],

            // Attributs UML (optionnels, non persistants)
            [['telephone', 'adresse', 'dateInscription'], 'safe'],
        ];
    }

    /**
     * Labels des attributs : utilisé dans les formulaires
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pseudo' => 'Pseudo',
            'pass' => 'Mot de passe',
            'nom' => 'Nom',
            'prenom' => 'Prénom',
            'mail' => 'Email',
            'photo' => 'Photo de profil',
            'permis' => 'Numéro de permis',

            // Attributs UML
            'telephone' => 'Téléphone',
            'adresse' => 'Adresse',
            'dateInscription' => 'Date d\'inscription',
        ];
    }

    /**
     * Relations : un internaute peut :
     * - proposer plusieurs voyages
     * - effectuer plusieurs réservations
     */
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['conducteur' => 'id']);
    }

    public function getReservations()
    {
        return $this->hasMany(Reservation::class, ['voyageur' => 'id']);
    }
}