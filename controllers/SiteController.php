<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * =============================
     * COMPORTEMENTS (Behaviors)
     * =============================
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
                        'allow' => true,
                        'roles' => ['@'], // Seuls les utilisateurs connectés peuvent se déconnecter
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'], // La déconnexion doit se faire en POST
                ],
            ],
        ];
    }

    /**
     * =============================
     * ACTIONS STANDARDS YII
     * =============================
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
     * =============================
     * PAGE D'ACCUEIL
     * =============================
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * =============================
     * CONNEXION (Login)
     * =============================
     */
    public function actionLogin()
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        // Si le formulaire est soumis et valide
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', "Connexion réussie !");
            return $this->goBack();
        }

        // Sinon, on affiche le formulaire (et on efface le mot de passe par sécurité)
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * =============================
     * DÉCONNEXION (Logout)
     * =============================
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}