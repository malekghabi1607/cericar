<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/**
 * @property string $pseudo
 * @property string $mail
 * @property string $pass
 */
class Internaute extends ActiveRecord implements IdentityInterface
{
    // Virtual attribute used for password confirmation on registration.
    public $password_repeat;
    public $photoFile;

    public static function tableName()
    {
        return 'fredouil.internaute';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['register'] = ['nom', 'prenom', 'pseudo', 'mail', 'pass', 'password_repeat', 'permis', 'photo', 'photoFile'];
        $scenarios['update'] = ['nom', 'prenom', 'pseudo', 'mail', 'permis', 'photo', 'photoFile'];
        return $scenarios;
    }

    // --- CORRECTION DES RÈGLES (Basé sur ton image) ---
    public function rules()
    {
        return [
            // Champs obligatoires (Note 'mail' et 'pass')
            [['nom', 'prenom', 'pseudo', 'mail'], 'required', 'on' => ['register', 'update'], 'message' => 'Ce champ est obligatoire.'],
            [['pass'], 'required', 'on' => 'register', 'message' => 'Ce champ est obligatoire.'],
            [['password_repeat'], 'required', 'on' => 'register', 'message' => 'Ce champ est obligatoire.'],
            [
                ['password_repeat'],
                'compare',
                'compareAttribute' => 'pass',
                'message' => 'Les mots de passe ne correspondent pas.',
                'on' => 'register',
            ],
            
            // Validation email sur le champ 'mail'
            [['mail'], 'email', 'message' => 'Format email invalide.'],
            
            // Unicité
            [['pseudo'], 'unique', 'message' => 'Ce pseudo est déjà pris.'],
            [['mail'], 'unique', 'message' => 'Cet email est déjà utilisé.'],
            
            // Champs optionnels (Note 'permis' et 'photo')
            ['permis', 'match', 'pattern' => '/^\d{12}$/', 'message' => 'Le permis doit contenir exactement 12 chiffres.'],
            [['photo'], 'string'],
            ['photo', 'validatePhoto'],
            [
                ['photoFile'],
                'file',
                'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp'],
                'checkExtensionByMimeType' => false,
                'skipOnEmpty' => true,
            ],
        ];
    }

    public function validatePhoto(string $attribute, $params = []): void
    {
        $value = trim((string)$this->$attribute);
        if ($value === '') {
            return;
        }

        if (preg_match('/^https?:\/\//i', $value)) {
            if (filter_var($value, FILTER_VALIDATE_URL) === false) {
                $this->addError($attribute, 'URL de photo invalide.');
            }
        }
    }

    // --- RELATIONS ---
    public function getVoyages() { return $this->hasMany(Voyage::class, ['conducteur' => 'id']); }
    public function getReservations() { return $this->hasMany(Reservation::class, ['voyageur' => 'id']); }

    // --- IDENTITÉ & SÉCURITÉ ---

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findByUsername($pseudo)
    {
        return static::findOne(['pseudo' => $pseudo]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function validatePassword($password)
    {
        // Accepte temporairement le clair + SHA1 pour compatibilité.
        return $this->pass === $password || $this->pass === sha1($password);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert && $this->scenario === 'register') {
            $this->pass = sha1($this->pass);
        }

        return true;
    }

    public function getAuthKey() { return null; }
    public function validateAuthKey($authKey) { return false; }
    public static function findIdentityByAccessToken($token, $type = null) { return null; }

    public static function estConducteur($pseudo) {
        $user = self::findByUsername($pseudo);
        // On vérifie le champ 'permis'
        return $user && !empty($user->permis);
    }
}
