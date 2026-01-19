<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Internaute;

class InternauteController extends Controller
{
    /**
     * ACTION DE TEST - ÉTAPE 2
     * Objectif : Afficher (sans mise en page) les infos d'un internaute,
     * ses voyages proposés et ses réservations.
     * * URL : index.php?r=internaute/test&pseudo=Fourmi
     */
    public function actionTest($pseudo = 'Fourmi')
    {
        // 1. 
        $internaute = Internaute::getUserByIdentifiant($pseudo);

        // Gestion d'erreur si le pseudo n'existe pas
        if (!$internaute) {
            return $this->render('test', [
                'error' => "L'utilisateur '$pseudo' est introuvable.",
                'internaute' => null,
                'voyages' => [],
                'reservations' => []
            ]);
        }

        // 2. On passe les objets à la vue (ActiveRecord gère les requêtes automatiquement)
        return $this->render('test', [
            'internaute'   => $internaute,
            'voyages'      => $internaute->voyages,       // Relation hasMany
            'reservations' => $internaute->reservations,  // Relation hasMany
        ]);
    }

    // Action bonus pour voir la liste complète
    public function actionIndex()
    {
        $internautes = Internaute::find()->all();
        return $this->render('index', ['internautes' => $internautes]);
    }


        /**
     * INSCRIPTION D’UN NOUVEL INTERNAUTE
     */
    public function actionRegister()
    {
        $model = new \app\models\Internaute();
        $model->scenario = 'register';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['site/register-success']);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

}