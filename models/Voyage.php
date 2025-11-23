<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.voyage".
 *
 * Base de données :
 * @property int $id
 * @property int $conducteur
 * @property int $trajet
 * @property int $idtypev
 * @property int $idmarquev
 * @property float $tarif
 * @property int $nbplacedispo
 * @property int $nbbagage
 * @property int $heuredepart
 * @property string|null $contraintes
 *
 * Attributs UML ajoutés (non persistants) :
 * @property string|null $dateDepart
 * @property string|null $heureArrivee
 * @property string|null $dateArrivee
 */
class Voyage extends ActiveRecord
{
    /**
     * Nom de la table PostgreSQL
     */
    public static function tableName()
    {
        return 'fredouil.voyage';
    }

    /**
     * Attributs UML non stockés en base
     */
    public $dateDepart;
    public $heureArrivee;
    public $dateArrivee;

    /**
     * Règles de validation
     */
    public function rules()
    {
        return [
            // Obligatoires BD
            [['conducteur', 'trajet', 'idtypev', 'idmarquev', 'tarif', 'nbplacedispo', 'nbbagage', 'heuredepart'], 'required'],

            // Types numériques
            [['conducteur', 'trajet', 'idtypev', 'idmarquev', 'nbplacedispo', 'nbbagage', 'heuredepart'], 'integer'],
            [['tarif'], 'number'],

            // Chaînes
            [['contraintes'], 'string', 'max' => 500],

            // Attributs UML ajoutés
            [['dateDepart', 'heureArrivee', 'dateArrivee'], 'safe'],
        ];
    }

    /**
     * Labels des attributs
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'conducteur' => 'Conducteur',
            'trajet' => 'Trajet',
            'idtypev' => 'Type de véhicule',
            'idmarquev' => 'Marque du véhicule',
            'tarif' => 'Tarif (€)',
            'nbplacedispo' => 'Places disponibles',
            'nbbagage' => 'Nombre de bagages',
            'heuredepart' => 'Heure de départ',
            'contraintes' => 'Contraintes',

            // Attributs UML
            'dateDepart' => 'Date de départ',
            'heureArrivee' => 'Heure d\'arrivée',
            'dateArrivee' => 'Date d\'arrivée',
        ];
    }

    /**
     * Relations :
     * Voyage → Internaute (conducteur)
     * Voyage → Trajet
     * Voyage → TypeVehicule
     * Voyage → MarqueVehicule
     * Voyage → Reservations
     */

    public function getConducteur0()
    {
        return $this->hasOne(Internaute::class, ['id' => 'conducteur']);
    }

    public function getTrajet0()
    {
        return $this->hasOne(Trajet::class, ['id' => 'trajet']);
    }

    public function getTypevehicule()
    {
        return $this->hasOne(Typevehicule::class, ['id' => 'idtypev']);
    }

    public function getMarquevehicule()
    {
        return $this->hasOne(Marquevehicule::class, ['id' => 'idmarquev']);
    }

    public function getReservations()
    {
        return $this->hasMany(Reservation::class, ['voyage' => 'id']);
    }
}
