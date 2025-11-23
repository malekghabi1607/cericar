<?php
namespace app\models;

use yii\base\BaseObject;
use yii\web\IdentityInterface;

class User extends BaseObject implements IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static function fromMyUsers(?MyUsers $row): ?self
    {
        if (!$row) return null;

        $u = new self();
        $u->id = $row->id;
        $u->username = $row->identifiant;
        $u->password = $row->motpasse;  
        return $u;
    }

    public static function findIdentity($id)
    {
        $row = MyUsers::findOne($id);
        return self::fromMyUsers($row);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsername($username)
    {
        $row = MyUsers::find()->where(['identifiant' => $username])->one();
        return self::fromMyUsers($row);
    }

    public static function findByIdentifiant($identifiant)
    {
        $row = MyUsers::find()->where(['identifiant' => $identifiant])->one();
        return self::fromMyUsers($row);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    // Vérifie le mot de passe (haché en SHA1 dans la base)
    public function validatePassword($password)
    {
        return sha1($password) === $this->password;
    }
}