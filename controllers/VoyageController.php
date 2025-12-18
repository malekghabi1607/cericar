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
        //RÉCUPÉRATION DES PARAMÈTRES DE RECHERCHE
        $vDepart = Yii::$app->request->get('depart');
        $vArrivee = Yii::$app->request->get('arrivee');
        $nbVoyageurs = Yii::$app->request->get('nb_pers');

        // Recherche lancée 

        // Une recherche est valide SI les trois champs ont été fournis.
        $rechercheLancee = (
            !empty($vDepart) &&
            !empty($vArrivee) &&
            !empty($nbVoyageurs)
        );

        // Initialiser un tableau vide qui contiendra tous les voyages trouvés + infos calculées.
        $resultats = [];

        // Indique si le trajet existe ou non
        $trajetExiste = false;

         //  TRAITEMENT DE LA RECHERCHE (SI CRITÈRES VALIDES)
        if ($rechercheLancee) {

            // Recherche du trajet correspondant (départ → arrivée)
            $trajet = Trajet::getTrajet($vDepart, $vArrivee);

            // Si le trajet existe => on cherche les voyages
            if ($trajet) {
                $trajetExiste = true;

                // Récupération de TOUS les voyages associés à ce trajet
                $voyages = Voyage::getVoyagesByTrajetId($trajet->id);
                
                // traiter chaque voyage un par un
                foreach ($voyages as $voyage) {

                    // Si nbplacedispo < nbVoyageurs => on NE MONTRE PAS ce voyage
                    if ($voyage->nbplacedispo < $nbVoyageurs) {
                        continue;
                    }

                    //  CALCUL DES PLACES RÉSERVÉES DANS CE VOYAGE
                    $placesReservees = Reservation::find()
                        ->where(['voyage' => $voyage->id])
                        //  ??0 Si sum() retourne null ( réservation), on met 0
                        ->sum('nbplaceresa') ?? 0;

                    // CALCUL DES PLACES RESTANTESaucune
                    $placesRestantes = $voyage->nbplacedispo - $placesReservees;

                    $estComplet = ($placesRestantes == 0);
                    $pasAssezPourDemande = ($placesRestantes < $nbVoyageurs);
                    $estDisponible = ($placesRestantes >= $nbVoyageurs);
                    $coutTotal = $trajet->distance * $voyage->tarif * $nbVoyageurs;

                    //  STOCKAGE DES DONNÉES POUR LA VUE
                    $resultats[] = [
                        'voyage' => $voyage,
                        'places_restantes' => $placesRestantes,
                        'pasAssezPourDemande' => $pasAssezPourDemande,
                        'est_complet' => $estComplet,
                        'est_disponible' => $estDisponible,
                        'cout_total' => $coutTotal
                    ];
                }
            }
        }

        //MODE AJAX — RENVOI JSON (SANS LAYOUT, SANS PAGE COMPLÈTE)
        if (Yii::$app->request->isAjax) {
            // On génère uniquement la vue partielle "_resultats.php"
            $html = $this->renderPartial('_resultats', [
                'resultats' => $resultats,
                'rechercheLancee' => $rechercheLancee,
                'vDepart' => $vDepart,
                'vArrivee' => $vArrivee,
                'nbVoyageurs' => $nbVoyageurs,
            ]);

            // Message à afficher dans le bandeau de notification du layout
            if (!$rechercheLancee) {
                $message = "Veuillez saisir vos critères";
            } elseif (!$trajetExiste) {
                $message = "Ce trajet n’existe pas";
            } elseif (empty($resultats)) {
                $message = "Aucun voyage trouvé";
            } else {
                $message = "Recherche terminée";
            }

            // Réponse JSON envoyée à jQuery
            return $this->asJson([
                'html' => $html,
                'message' => $message,
            ]);
        }

        //MODE NORMAL __ AFFICHAGE PAGE COMPLÈTE (LAYOUT + VUE)
        return $this->render('recherche', [
            'resultats' => $resultats,
            'rechercheLancee' => $rechercheLancee,
            'vDepart' => $vDepart,
            'vArrivee' => $vArrivee,
            'nbVoyageurs' => $nbVoyageurs,
        ]);
    }


}
