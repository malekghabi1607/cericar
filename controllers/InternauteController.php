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
        // 1. Utilisation de la méthode statique imposée par le sujet [Source: 83]
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
}