<?php
use yii\helpers\Html;

/* @var $resultats array */
/* @var $rechercheLancee boolean */
/* @var $vDepart string */
/* @var $vArrivee string */
/* @var $nbVoyageurs int */
?>
 <!-- On n’affiche rien tant que l’utilisateur n’a pas lancé une recherche
	ca evite d’avoir une page vide ou des erreurs au chargement -->

<?php if ($rechercheLancee): ?>

    <!-- ===== CONTEXTE DE RECHERCHE ===== -->
    <div class="result-context">
        Trajets de <?= Html::encode($vDepart) ?> → <?= Html::encode($vArrivee) ?>
        pour <?= Html::encode($nbVoyageurs) ?> voyageur(s).<br>
        <strong><?= count($resultats) ?> trajet(s) trouvé(s).</strong>
    </div>

    <?php if (empty($resultats)): ?>

        <div class="voyage-card no-result">
            Aucun voyage direct trouvé pour ce trajet.
        </div>

    <?php else: ?>

        <?php foreach ($resultats as $data): ?>
            <?php
                 // Récupération de l'objet Voyage       
                $voyage = $data['voyage'];

                //  Ces calculs sont faits côté vue car ils concernent uniquement l’affichage et respectent l’énoncé.

                // ===== CALCULS CONFORMES À L’ÉNONCÉ (1 km = 1 minute selon l'énoncé) =====
                $distance = $voyage->trajetInfo->distance; // km = minutes
                $dureeMinutes = $distance;
                $dureeHeures = floor($dureeMinutes / 60);
                $dureeRestantes = $dureeMinutes % 60;

                // Heure d’arrivée
                $heureDepart = (int) $voyage->heuredepart;
                $minutesArrivee = $heureDepart * 60 + $dureeMinutes;
                $heureArrivee = floor(($minutesArrivee / 60) % 24);
                $minuteArrivee = $minutesArrivee % 60;

                // Classe CSS
                $cssClass = $data['est_complet']
                    ? 'complet'
                    : ($data['est_disponible'] ? 'disponible' : 'partiel');
            ?>

            <!-- ===== CARTE TRAJET (LISTE) ===== -->
            <div class="voyage-card <?= $cssClass ?>">

                <!-- ===== LIGNE PRINCIPALE  ===== -->
                <div class="voyage-line">

                    <div class="heure-ville">
                        <strong><?= $voyage->heuredepart ?>h</strong><br>
                        <small><?= Html::encode($voyage->trajetInfo->depart) ?></small>
                    </div>

                    <div class="trajet-ligne">
                        <span class="distance"><?= $distance ?> km</span><br>
                        <span class="duree">
                            Durée : <?= $dureeHeures ?>h<?= str_pad($dureeRestantes, 2, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>

                    <div class="heure-ville">
                        <strong><?= $heureArrivee ?>h<?= str_pad($minuteArrivee, 2, '0', STR_PAD_LEFT) ?></strong><br>
                        <small><?= Html::encode($voyage->trajetInfo->arrivee) ?></small>
                    </div>

                    <div class="prix">
                        <?= number_format($data['cout_total'], 2) ?> €
                    </div>

                </div>

                <!-- ===== INFOS PLACES ===== -->
                <div class="places-info">

                    <span class="place-item">
                        <i class="fa-solid fa-user"></i>
                        Demandées : <strong><?= $nbVoyageurs ?></strong>
                    </span>

                    <span class="place-item">
                        <i class="fa-solid fa-chair"></i>
                        Totales : <strong><?= $voyage->nbplacedispo ?></strong>
                    </span>

                    <span class="place-item">
                        <i class="fa-solid fa-check-circle"></i>
                        Restantes : <strong><?= $data['places_restantes'] ?></strong>
                    </span>

                </div>

                <!-- ===== STATUT ===== -->
                <div class="voyage-status">
                    <?php if ($data['est_complet']): ?>
                        <span class="etat complet">COMPLET</span>
                    <?php elseif ($data['pasAssezPourDemande']): ?>
                        <span class="etat manque">Places insuffisantes</span>
                    <?php else: ?>
                        <span class="etat disponible">Disponible</span>
                    <?php endif; ?>
                </div>

                <!-- ===== DÉTAILS AU CLIC ===== -->
 
                <details class="voyage-details">
                    <summary>Voir les détails</summary>

                    <div class="details-content">

                        <!-- ===== COLONNE GAUCHE : DÉTAILS DU VOYAGE ===== -->
                        <div class="details-left">

                            <div class="detail-item">
                                <span class="icon"><i class="fa-solid fa-car"></i></span>
                                <div>
                                    <strong>ID Voyage</strong><br>
                                    <?= $voyage->id ?>
                                </div>
                            </div>

                            <div class="detail-item">
                                <span class="icon"><i class="fa-solid fa-chair"></i></span>
                                <div>
                                    <strong>Places restantes</strong><br>
                                    <?= $data['places_restantes'] ?>
                                </div>
                            </div>

                            <div class="detail-item">
                                <span class="icon"><i class="fa-solid fa-car-side"></i></span>
                                <div>
                                    <strong>Véhicule</strong><br>
                                    <?= Html::encode($voyage->marqueVehicule->marquev) ?>
                                    – <?= Html::encode($voyage->typeVehicule->typev) ?>
                                </div>
                            </div>

                            <div class="detail-item">
                                <span class="icon"><i class="fa-solid fa-suitcase"></i></span>
                                <div>
                                    <strong>Bagages</strong><br>
                                    2 valises max
                                </div>
                            </div>

                            <div class="detail-item">
                                <span class="icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                                <div>
                                    <strong>Contraintes</strong><br>
                                    <?= Html::encode($voyage->contraintes ?: 'Aucune') ?>
                                </div>
                            </div>

                        </div>

                        <!-- ===== COLONNE DROITE : CONDUCTEUR ===== -->
                        <div class="details-right">

                            <div class="detail-item">
                                <span class="icon"><i class="fa-solid fa-user"></i></span>
                                <div>
                                    <strong><?= Html::encode($voyage->conducteurInfo->pseudo) ?></strong><br>
                                    Conducteur
                                </div>
                            </div>

                            <div class="detail-item">
                                <span class="icon"><i class="fa-solid fa-phone"></i></span>
                                <div>
                                    <strong>Téléphone</strong><br>
                                    06 11 22 33 44
                                </div>
                            </div>

                        </div>

                        <!-- ===== RÉSERVATION ===== -->
                        <div class="reservation-box">

                            <div class="reservation-row">
                                <div>
                                    <label>Nombre de places à réserver</label>
                                    <input type="number" value="<?= $nbVoyageurs ?>" readonly>
                                </div>

                                <div>
                                    <label>Prix total</label>
                                    <input type="text"
                                        value="<?= number_format($data['cout_total'], 2) ?> €"
                                        readonly>
                                </div>
                            </div>

                            <button class="btn-reserver">
                                RÉSERVER
                            </button>

                        </div>

                    </div>
                </details>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

<?php else: ?>

    <p class="info-init">
    Veuillez saisir vos critères pour lancer la recherche.
</p>

<?php endif; ?>