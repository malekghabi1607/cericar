<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Trajet;
use app\models\Voyage;
use app\models\Reservation;
use app\models\Internaute;
use app\models\TypeVehicule;
use app\models\MarqueVehicule;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use Yii;

class VoyageController extends Controller
{   
   public function actionRecherche()
{
    // Récupération des paramètres saoit du submit classique ou d une requete ajax envoyés par le formulaire
    $vDepart = Yii::$app->request->get('depart');
    $vArrivee = Yii::$app->request->get('arrivee');
    $nbVoyageurs = Yii::$app->request->get('nb_pers');
    $withCorrespondances = Yii::$app->request->get('with_correspondances') ? true : false;

    // Validation de la recherche / ca empeche la recherche incomplete
    $rechercheLancee = (
        !empty($vDepart) &&
        !empty($vArrivee) &&
        !empty($nbVoyageurs)
    );

     // Initialisation des variables
    $resultats = [];        // Résultats directs à afficher
    $correspondances = [];  // Trajets avec correspondances
    $trajetExiste = false;  // Sert pour les messages utilisateur
    $hasAnyVoyage = false;  // Sert pour les messages utilisateur

    //  Traitement métier
    if ($rechercheLancee) {

        $paths = Trajet::findPaths($vDepart, $vArrivee, 2);

        if (!empty($paths)) {
            $trajetExiste = true;
        }

        foreach ($paths as $path) {
            if (count($path) === 1) {
                $trajet = $path[0];
                $voyages = Voyage::getVoyagesByTrajetId($trajet->id);

                foreach ($voyages as $voyage) {
                    // On ignore les voyages annules par le conducteur
                    if ($voyage->contraintes && stripos($voyage->contraintes, 'ANNULE') !== false) {
                        continue;
                    }
                    // On ignore les voyages sans assez de places
                    if ($voyage->nbplacedispo < $nbVoyageurs) {
                        continue;
                    }
                    // Calcul des places déjà réservées
                    $placesReservees = Reservation::find()
                        ->where(['voyage' => $voyage->id])
                        ->sum('nbplaceresa') ?? 0;
                    // Calcul des places restantes (évite les valeurs négatives)
                    $placesRestantes = max(0, $voyage->nbplacedispo - $placesReservees);

                    $hasAnyVoyage = true;
                    // Préparation des données pour la vue
                    $resultats[] = [
                        'voyage' => $voyage,
                        'places_restantes' => $placesRestantes,
                        'pasAssezPourDemande' => ($placesRestantes < $nbVoyageurs),
                        'est_complet' => ($placesRestantes <= 0),
                        'est_disponible' => ($placesRestantes >= $nbVoyageurs),
                        'cout_total' => $trajet->distance * $voyage->tarif * $nbVoyageurs
                    ];
                }
                continue;
            }

            if (!$withCorrespondances) {
                continue;
            }

            $segments = [];
            $hasAllSegments = true;
            $totalDistance = 0;
            foreach ($path as $segmentTrajet) {
                $totalDistance += (int)$segmentTrajet->distance;
                $segmentVoyages = Voyage::getVoyagesByTrajetId($segmentTrajet->id);
                $segmentResults = [];
                $segmentHasAvailable = false;

                foreach ($segmentVoyages as $voyage) {
                    if ($voyage->contraintes && stripos($voyage->contraintes, 'ANNULE') !== false) {
                        continue;
                    }
                    if ($voyage->nbplacedispo < $nbVoyageurs) {
                        continue;
                    }

                    $placesReservees = Reservation::find()
                        ->where(['voyage' => $voyage->id])
                        ->sum('nbplaceresa') ?? 0;
                    $placesRestantes = max(0, $voyage->nbplacedispo - $placesReservees);

                    $hasAnyVoyage = true;
                    if ($placesRestantes >= $nbVoyageurs) {
                        $segmentHasAvailable = true;
                    }
                    $segmentResults[] = [
                        'voyage' => $voyage,
                        'places_restantes' => $placesRestantes,
                        'pasAssezPourDemande' => ($placesRestantes < $nbVoyageurs),
                        'est_complet' => ($placesRestantes <= 0),
                        'est_disponible' => ($placesRestantes >= $nbVoyageurs),
                        'cout_total' => $segmentTrajet->distance * $voyage->tarif * $nbVoyageurs
                    ];
                }

                $segments[] = [
                    'trajet' => $segmentTrajet,
                    'voyages' => $segmentResults,
                ];

                if (!$segmentHasAvailable) {
                    $hasAllSegments = false;
                }
            }

            if ($totalDistance > 24 * 60) {
                continue;
            }

            $cities = [$vDepart];
            foreach ($path as $segmentTrajet) {
                $cities[] = $segmentTrajet->arrivee;
            }

            if ($hasAllSegments) {
                $correspondances[] = [
                    'cities' => $cities,
                    'segments' => $segments,
                    'nb_correspondances' => count($path) - 1,
                ];
            }
        }
    }

    // MODE AJAX — RENVOI JSON (SANS LAYOUT)
if (Yii::$app->request->isAjax) {
  // Génération du HTML des résultats uniquement (sans layout)
    $html = $this->renderPartial('_resultats', [
        'resultats' => $resultats,
        'correspondances' => $correspondances,
        'withCorrespondances' => $withCorrespondances,
        'rechercheLancee' => $rechercheLancee,
        'vDepart' => $vDepart,
        'vArrivee' => $vArrivee,
        'nbVoyageurs' => $nbVoyageurs,
    ]);

     // Messages personnalisés pour le bandeau de notification
        $totalResults = count($resultats) + ($withCorrespondances ? count($correspondances) : 0);

        if (!$rechercheLancee) {

            $message = "Merci de compléter tous les champs pour lancer la recherche.";
            $type = "warning";

        } elseif (!$trajetExiste) {

            $message = "Aucun trajet enregistré entre {$vDepart} et {$vArrivee} (même avec correspondances).";
            $type = "error";

        } elseif ($trajetExiste && !$hasAnyVoyage) {

            $message = "Des trajets existent entre {$vDepart} et {$vArrivee}, mais aucun voyage n’est proposé pour le moment.";
            $type = "info";

        } elseif ($withCorrespondances && empty($resultats) && !empty($correspondances)) {

            $message = "Aucun voyage direct disponible, mais des correspondances existent.";
            $type = "warning";

        } elseif ($totalResults === 1) {

            $message = "Un voyage correspondant à votre recherche est disponible.";
            $type = "success";

        } else {

            $message = $totalResults . " trajet(s) correspondent à votre recherche.";
            $type = "success";
        }
   // Réponse JSON envoyée au JavaScript
        return $this->asJson([
            'html' => $html,
            'message' => $message,
            'type' => $type,
        ]);
    
    }

    // MODE NORMAL
    return $this->render('recherche', [
        'resultats' => $resultats,
        'correspondances' => $correspondances,
        'withCorrespondances' => $withCorrespondances,
        'rechercheLancee' => $rechercheLancee,
        'vDepart' => $vDepart,
        'vArrivee' => $vArrivee,
        'nbVoyageurs' => $nbVoyageurs,
    ]);
}

public function actionReserver()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    // 🔐 Sécurité : internaute obligatoire
    if (!Yii::$app->session->has('internaute')) {
        return [
            'status' => 'error',
            'message' => 'Vous devez être connecté pour réserver.'
        ];
    }

    try {
        $internaute = Yii::$app->session->get('internaute');

        $idVoyage = (int)Yii::$app->request->post('voyage_id');
        $nbPlaces = (int)Yii::$app->request->post('nb_places', 1);

        $voyage = Voyage::findOne($idVoyage);
        if (!$voyage) {
            return [
                'status' => 'error',
                'message' => 'Voyage introuvable.'
            ];
        }

        $placesReservees = Reservation::find()
            ->where(['voyage' => $idVoyage])
            ->sum('nbplaceresa') ?? 0;
        $placesRestantes = max(0, $voyage->nbplacedispo - $placesReservees);

        if ($nbPlaces > $placesRestantes) {
            return [
                'status' => 'error',
                'message' => 'Places insuffisantes pour cette réservation.'
            ];
        }

        $reservation = new Reservation();
        $reservation->voyage = $idVoyage;
        $reservation->voyageur = (int)$internaute['id'];
        $reservation->nbplaceresa = $nbPlaces;

        if (!$reservation->save()) {
            return [
                'status' => 'error',
                'message' => 'Erreur validation : ' . json_encode($reservation->getErrors())
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Réservation enregistrée'
        ];

    } catch (\Throwable $e) {
        return [
            'status' => 'error',
            'message' => 'Erreur serveur : ' . $e->getMessage()
        ];
    }
}

public function actionReserverCorrespondance()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    if (!Yii::$app->session->has('internaute')) {
        return [
            'status' => 'error',
            'message' => 'Vous devez être connecté pour réserver.'
        ];
    }

    $voyageIds = Yii::$app->request->post('voyages', []);
    if (is_string($voyageIds)) {
        $decoded = json_decode($voyageIds, true);
        if (is_array($decoded)) {
            $voyageIds = $decoded;
        }
    }

    $voyageIds = array_values(array_unique(array_map('intval', (array)$voyageIds)));
    $voyageIds = array_filter($voyageIds);

    if (empty($voyageIds)) {
        return [
            'status' => 'error',
            'message' => 'Aucun voyage disponible pour cette correspondance.'
        ];
    }

    $nbPlaces = (int)Yii::$app->request->post('nb_places', 1);
    if ($nbPlaces < 1) {
        $nbPlaces = 1;
    }

    $transaction = Yii::$app->db->beginTransaction();
    try {
        $internaute = Yii::$app->session->get('internaute');

        foreach ($voyageIds as $idVoyage) {
            $voyage = Voyage::findOne((int)$idVoyage);
            if (!$voyage) {
                $transaction->rollBack();
                return [
                    'status' => 'error',
                    'message' => 'Voyage introuvable pour la correspondance.'
                ];
            }

            $placesReservees = Reservation::find()
                ->where(['voyage' => $voyage->id])
                ->sum('nbplaceresa') ?? 0;
            $placesRestantes = max(0, $voyage->nbplacedispo - $placesReservees);

            if ($nbPlaces > $placesRestantes) {
                $transaction->rollBack();
                return [
                    'status' => 'error',
                    'message' => 'Places insuffisantes pour un segment de la correspondance.'
                ];
            }

            $reservation = new Reservation();
            $reservation->voyage = (int)$voyage->id;
            $reservation->voyageur = (int)$internaute['id'];
            $reservation->nbplaceresa = $nbPlaces;

            if (!$reservation->save()) {
                $transaction->rollBack();
                return [
                    'status' => 'error',
                    'message' => 'Erreur validation : ' . json_encode($reservation->getErrors())
                ];
            }
        }

        $transaction->commit();

        return [
            'status' => 'success',
            'type' => 'success',
            'message' => 'Correspondance réservée.'
        ];
    } catch (\Throwable $e) {
        if ($transaction->isActive) {
            $transaction->rollBack();
        }
        return [
            'status' => 'error',
            'message' => 'Erreur serveur : ' . $e->getMessage()
        ];
    }
}

public function actionMesReservations()
{
    if (!Yii::$app->session->has('internaute')) {
        return $this->redirect(['site/login']);
    }

    $internaute = Yii::$app->session->get('internaute');

    $reservations = Reservation::find()
        ->where(['voyageur' => $internaute['id']])
        ->with(['voyage.trajet'])
        ->all();

    return $this->render('mes-reservations', [
        'reservations' => $reservations
    ]);
}

public function actionCreate()
{
    if (!Yii::$app->session->has('internaute')) {
        return $this->redirect(['site/login']);
    }

    $sessionUser = Yii::$app->session->get('internaute');
    $user = Internaute::findOne((int)$sessionUser['id']);
    if (!$user || empty($user->permis)) {
        return $this->render('permis-required');
    }

    $voyage = new Voyage();

    if ($voyage->load(Yii::$app->request->post())) {
        $voyage->conducteur = $user->id;

        if ($voyage->save()) {
            Yii::$app->session->setFlash('success', 'Voyage propose avec succes.');
            if (Yii::$app->request->isAjax) {
                return $this->asJson([
                    'status' => 'success',
                    'type' => 'success',
                    'message' => 'Voyage propose avec succes.',
                    'redirect' => \yii\helpers\Url::to(['site/profil']),
                ]);
            }
            return $this->redirect(['site/profil']);
        }
    }

    if (Yii::$app->request->isAjax && $voyage->hasErrors()) {
        $firstError = implode(' ', $voyage->getFirstErrors());
        return $this->asJson([
            'status' => 'error',
            'type' => 'error',
            'message' => $firstError ?: 'Erreur lors de la proposition du voyage.',
        ]);
    }

    return $this->render('create', [
        'voyage' => $voyage,
        'trajets' => Trajet::find()->all(),
        'types' => TypeVehicule::find()->all(),
        'marques' => MarqueVehicule::find()->all(),
    ]);
}

public function actionUpdate($id)
{
    if (!Yii::$app->session->has('internaute')) {
        return $this->redirect(['site/login']);
    }

    $voyage = $this->findModelForUser((int)$id);

    if ($voyage->load(Yii::$app->request->post()) && $voyage->save()) {
        Yii::$app->session->setFlash('success', 'Voyage modifie avec succes.');
        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'status' => 'success',
                'type' => 'success',
                'message' => 'Voyage modifie avec succes.',
                'redirect' => \yii\helpers\Url::to(['site/profil']),
            ]);
        }
        return $this->redirect(['site/profil']);
    }

    if (Yii::$app->request->isAjax && $voyage->hasErrors()) {
        $firstError = implode(' ', $voyage->getFirstErrors());
        return $this->asJson([
            'status' => 'error',
            'type' => 'error',
            'message' => $firstError ?: 'Erreur lors de la modification du voyage.',
        ]);
    }

    return $this->render('update', [
        'voyage' => $voyage,
        'trajets' => Trajet::find()->all(),
        'types' => TypeVehicule::find()->all(),
        'marques' => MarqueVehicule::find()->all(),
    ]);
}

public function actionDelete($id)
{
    if (!Yii::$app->session->has('internaute')) {
        return $this->redirect(['site/login']);
    }

    if (!Yii::$app->request->isPost) {
        return $this->redirect(['site/profil']);
    }

    $voyage = $this->findModelForUser((int)$id);
    $hasReservations = Reservation::find()
        ->where(['voyage' => $voyage->id])
        ->exists();
    if ($hasReservations) {
        $constraints = trim((string)$voyage->contraintes);
        if (stripos($constraints, 'ANNULE') === false) {
            $voyage->contraintes = trim('ANNULE PAR LE CONDUCTEUR. ' . $constraints);
        }
        $voyage->save(false);
        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'status' => 'success',
                'type' => 'warning',
                'message' => 'Voyage annule. Les voyageurs seront informes.',
                'redirect' => \yii\helpers\Url::to(['site/profil']),
            ]);
        }
        Yii::$app->session->setFlash('success', 'Voyage annule. Les voyageurs seront informes.');
        return $this->redirect(['site/profil']);
    }

    $voyage->delete();

    if (Yii::$app->request->isAjax) {
        return $this->asJson([
            'status' => 'success',
            'type' => 'success',
            'message' => 'Voyage supprime.',
            'redirect' => \yii\helpers\Url::to(['site/profil']),
        ]);
    }
    Yii::$app->session->setFlash('success', 'Voyage supprime.');
    return $this->redirect(['site/profil']);
}

public function actionReactivate($id)
{
    if (!Yii::$app->session->has('internaute')) {
        return $this->redirect(['site/login']);
    }

    if (!Yii::$app->request->isPost) {
        return $this->redirect(['site/profil']);
    }

    $voyage = $this->findModelForUser((int)$id);
    $constraints = trim((string)$voyage->contraintes);
    if (stripos($constraints, 'ANNULE') === false) {
        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'status' => 'error',
                'type' => 'error',
                'message' => 'Ce voyage est deja actif.',
            ]);
        }
        Yii::$app->session->setFlash('error', 'Ce voyage est deja actif.');
        return $this->redirect(['site/profil']);
    }

    $prefix = 'ANNULE PAR LE CONDUCTEUR.';
    if (stripos($constraints, $prefix) === 0) {
        $constraints = trim(substr($constraints, strlen($prefix)));
    } else {
        $constraints = trim(str_ireplace('ANNULE PAR LE CONDUCTEUR.', '', $constraints));
    }

    $voyage->contraintes = $constraints;
    $voyage->save(false);

    if (Yii::$app->request->isAjax) {
        return $this->asJson([
            'status' => 'success',
            'type' => 'success',
            'message' => 'Voyage revalide.',
            'redirect' => \yii\helpers\Url::to(['site/profil']),
        ]);
    }
    Yii::$app->session->setFlash('success', 'Voyage revalide.');
    return $this->redirect(['site/profil']);
}

public function actionForceDelete($id)
{
    if (!Yii::$app->session->has('internaute')) {
        return $this->redirect(['site/login']);
    }

    if (!Yii::$app->request->isPost) {
        return $this->redirect(['site/profil']);
    }

    $voyage = $this->findModelForUser((int)$id);
    $constraints = trim((string)$voyage->contraintes);
    if (stripos($constraints, 'ANNULE') === false) {
        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'status' => 'error',
                'type' => 'error',
                'message' => 'Le voyage doit etre annule avant suppression totale.',
            ]);
        }
        Yii::$app->session->setFlash('error', 'Le voyage doit etre annule avant suppression totale.');
        return $this->redirect(['site/profil']);
    }

    Reservation::deleteAll(['voyage' => $voyage->id]);
    $voyage->delete();

    if (Yii::$app->request->isAjax) {
        return $this->asJson([
            'status' => 'success',
            'type' => 'success',
            'message' => 'Voyage supprime definitivement.',
            'redirect' => \yii\helpers\Url::to(['site/profil']),
        ]);
    }
    Yii::$app->session->setFlash('success', 'Voyage supprime definitivement.');
    return $this->redirect(['site/profil']);
}

private function findModelForUser(int $id): Voyage
{
    $sessionUser = Yii::$app->session->get('internaute');
    $userId = (int)$sessionUser['id'];

    $voyage = Voyage::find()
        ->where(['id' => $id, 'conducteur' => $userId])
        ->one();

    if (!$voyage) {
        throw new NotFoundHttpException('Voyage introuvable.');
    }

    return $voyage;
}


}
