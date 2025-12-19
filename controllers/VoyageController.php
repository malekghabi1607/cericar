<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Trajet;
use app\models\Voyage;
use app\models\Reservation;
use Yii;

class VoyageController extends Controller
{   
   public function actionRecherche()
{
    // Récupération des paramètres saoit du submit classique ou d une requete ajax envoyés par le formulaire
    $vDepart = Yii::$app->request->get('depart');
    $vArrivee = Yii::$app->request->get('arrivee');
    $nbVoyageurs = Yii::$app->request->get('nb_pers');

    // Validation de la recherche / ca empeche la recherche incomplete
    $rechercheLancee = (
        !empty($vDepart) &&
        !empty($vArrivee) &&
        !empty($nbVoyageurs)
    );

     // Initialisation des variables
    $resultats = [];        // Résultats à afficher
    $trajetExiste = false; // Sert pour les messages utilisateur
    $voyages = [];         // Liste brute des voyages trouvés

    //  Traitement métier
    if ($rechercheLancee) {

        $trajet = Trajet::getTrajet($vDepart, $vArrivee);

        if ($trajet) {
            $trajetExiste = true;
            // Récupération des voyages associés à ce trajet
            $voyages = Voyage::getVoyagesByTrajetId($trajet->id);

            foreach ($voyages as $voyage) {
          // On ignore les voyages sans assez de places
                if ($voyage->nbplacedispo < $nbVoyageurs) {
                    continue;
                }
          // Calcul des places déjà réservées
                $placesReservees = Reservation::find()
                    ->where(['voyage' => $voyage->id])
                    ->sum('nbplaceresa') ?? 0;
        // Calcul des places restantes
                $placesRestantes = $voyage->nbplacedispo - $placesReservees;
       // Préparation des données pour la vue
                $resultats[] = [
                    'voyage' => $voyage,
                    'places_restantes' => $placesRestantes,
                    'pasAssezPourDemande' => ($placesRestantes < $nbVoyageurs),
                    'est_complet' => ($placesRestantes == 0),
                    'est_disponible' => ($placesRestantes >= $nbVoyageurs),
                    'cout_total' => $trajet->distance * $voyage->tarif * $nbVoyageurs
                ];
            }
        }
    }

    // MODE AJAX — RENVOI JSON (SANS LAYOUT)
if (Yii::$app->request->isAjax) {
  // Génération du HTML des résultats uniquement (sans layout)
    $html = $this->renderPartial('_resultats', [
        'resultats' => $resultats,
        'rechercheLancee' => $rechercheLancee,
        'vDepart' => $vDepart,
        'vArrivee' => $vArrivee,
        'nbVoyageurs' => $nbVoyageurs,
    ]);

     // Messages personnalisés pour le bandeau de notification
        if (!$rechercheLancee) {

            $message = "Merci de compléter tous les champs pour lancer la recherche.";
            $type = "warning";

        } elseif (!$trajetExiste) {

            $message = "Aucun trajet enregistré entre {$vDepart} et {$vArrivee}.";
            $type = "error";

        } elseif ($trajetExiste && empty($voyages)) {

            $message = "Des trajets existent entre {$vDepart} et {$vArrivee}, mais aucun voyage n’est proposé pour le moment.";
            $type = "info";

        } elseif (!empty($voyages) && empty($resultats)) {

            $message = "Des voyages existent, mais aucun ne dispose d’assez de places pour {$nbVoyageurs} voyageur(s).";
            $type = "warning";

        } elseif (count($resultats) === 1) {

            $message = "Un voyage correspondant à votre recherche est disponible.";
            $type = "success";

        } else {

            $message = count($resultats) . " voyages correspondent à votre recherche.";
            $type = "success";
        }
   // Réponse JSON envoyée au JavaScript
        return $this->asJson([
            'html' => $html,
            'message' => $message,
            'type' => $type,
        ]);
    

        return $this->asJson([
            'html' => $html,
            'message' => $message,
            'type' => $type,
        ]);
    }

    // MODE NORMAL
    return $this->render('recherche', [
        'resultats' => $resultats,
        'rechercheLancee' => $rechercheLancee,
        'vDepart' => $vDepart,
        'vArrivee' => $vArrivee,
        'nbVoyageurs' => $nbVoyageurs,
    ]);
}
}
