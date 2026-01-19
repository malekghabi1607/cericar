<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Internaute;

/**
 * LoginForm
 * Gère la connexion d’un internaute
 */
class LoginForm extends Model
{
    public $username;   // pseudo
    public $password;   // mot de passe en clair

    /** @var Internaute|null */
    private $_user = null;

    /**
     * Règles de validation
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Vérifie le mot de passe
     */
    public function validatePassword($attribute, $params)
    {
        if ($this->hasErrors()) {
            return;
        }

        $user = $this->getUser();

        if (!$user || $user->pass !== sha1($this->password)) {
            $this->addError($attribute, 'Identifiant ou mot de passe incorrect.');
        }
    }

    /**
     * Connexion de l’utilisateur
     */
   public function login()
{
    if ($this->validate()) {
        return Yii::$app->user->login($this->getUser());
    }
    return false;
}



    /**
     * Récupère l’internaute à partir du pseudo
     */
  protected function getUser()
{
    if ($this->_user === null) {
        $this->_user = User::findByPseudo($this->pseudo);
    }
    return $this->_user;
}
}