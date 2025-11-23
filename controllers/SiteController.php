<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Produits;
use app\models\MyUsers;

class SiteController extends Controller
{
    /**
     * =====================================================
     * COMPORTEMENTS GÉNÉRAUX DU CONTRÔLEUR
     * =====================================================
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'], // uniquement les utilisateurs connectés
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'], // logout uniquement en POST (sécurité)
                ],
            ],
        ];
    }

    /**
     * =====================================================
     * ACTIONS GÉNÉRIQUES DE YII (erreurs, captcha)
     * =====================================================
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * =====================================================
     * PAGE D’ACCUEIL
     * =====================================================
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * =====================================================
     * AUTHENTIFICATION (TP2)
     * =====================================================
     */

    /**
     * Page de connexion utilisateur
     */
    public function actionLogin()
    {
        // Si déjà connecté, on retourne à l'accueil
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        // Traitement du formulaire de connexion
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Message de bienvenue personnalisé
            $username = ucfirst(Yii::$app->user->identity->username);
            Yii::$app->session->setFlash('success', "Connexion réussie : Bienvenue $username !");
            return $this->goHome();
        }

        // Réinitialiser le champ mot de passe
        $model->password = '';

        // Affichage du formulaire
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Déconnexion (POST obligatoire)
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * =====================================================
     * PAGES COMPLÉMENTAIRES
     * =====================================================
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * =====================================================
     * TP1 – MA PAGE PERSONNALISÉE
     * =====================================================
     */

    /**
     * Affiche la page “Ma Page”
     * Message paramétrable via ?message=... et
     * liste déroulante basée sur le modèle Produits
     */
    public function actionMapage($message = 'Hello World')
    {
        $produits = new Produits(); // instanciation du modèle

        return $this->render('mapage', [
            'message'  => $message,
            'produits' => $produits, // on envoie l’objet à la vue
        ]);
    }



public function actionTables()
{
    $tables = \Yii::$app->db->schema->getTableNames();

    echo "<h1>Tables présentes dans la base :</h1>";
    echo "<ul>";
    foreach ($tables as $t) {
        echo "<li>$t</li>";
    }
    echo "</ul>";
}

public function actionCheckSchema()
{
    try {
        $schema = \Yii::$app->db->createCommand("SHOW search_path")->queryScalar();
        echo "<h1>search_path = $schema</h1>";
    } catch (\Exception $e) {
        echo "<pre>" . $e->getMessage() . "</pre>";
    }
}
}