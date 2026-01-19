<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

/* @var $resultats array */
/* @var $correspondances array */
/* @var $withCorrespondances bool */
/* @var $rechercheLancee boolean */
/* @var $vDepart string */
/* @var $vArrivee string */
/* @var $nbVoyageurs int */



// Détection de l'état de connexion via votre session personnalisée
$isGuest = !Yii::$app->session->has('id_internaute');
$correspondances = $correspondances ?? [];
$withCorrespondances = $withCorrespondances ?? false;
$totalResults = count($resultats) + ($withCorrespondances ? count($correspondances) : 0);
?>

<?php if ($rechercheLancee): ?>

    <div class="result-context">
        Trajets de <?= Html::encode($vDepart) ?> → <?= Html::encode($vArrivee) ?>
        pour <?= Html::encode($nbVoyageurs) ?> voyageur(s).<br>
        <strong><?= $totalResults ?> trajet(s) trouvé(s).</strong>
    </div>

    <?php if (empty($resultats) && empty($correspondances)): ?>
        <div class="voyage-card no-result">
            Aucun voyage direct trouvé pour ce trajet.
        </div>
    <?php else: ?>

        <?php if (!empty($resultats)): ?>
            <?php foreach ($resultats as $data): ?>
            <?php
                $voyage = $data['voyage'];

                // CALCULS DE TEMPS (1 km = 1 minute)
                $distance = $voyage->trajetInfo->distance;
                $dureeMinutes = $distance;
                $dureeHeures = floor($dureeMinutes / 60);
                $dureeRestantes = $dureeMinutes % 60;

                // Calcul de l'heure d'arrivée
                $heureDepart = (int) $voyage->heuredepart;
                $minutesArrivee = $heureDepart * 60 + $dureeMinutes;
                $heureArrivee = floor(($minutesArrivee / 60) % 24);
                $minuteArrivee = $minutesArrivee % 60;

                $cssClass = $data['est_complet'] ? 'complet' : ($data['est_disponible'] ? 'disponible' : 'partiel');
            ?>

            <div class="voyage-card <?= $cssClass ?>">
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

                <div class="places-info">
                    <span class="place-item"><i class="fa-solid fa-user"></i> Demandées : <?= $nbVoyageurs ?></span>
                    <span class="place-item"><i class="fa-solid fa-chair"></i> Totales : <?= $voyage->nbplacedispo ?></span>
                    <span class="place-item"><i class="fa-solid fa-check-circle"></i> Restantes : <?= $data['places_restantes'] ?></span>
                </div>

                <div class="voyage-status">
                    <?php if ($data['est_complet']): ?>
                        <span class="etat complet">COMPLET</span>
                    <?php elseif ($data['pasAssezPourDemande']): ?>
                        <span class="etat manque">Places insuffisantes</span>
                    <?php else: ?>
                        <span class="etat disponible">Disponible</span>
                    <?php endif; ?>
                </div>

                <details class="voyage-details">
                    <summary>Voir les détails</summary>
                    <div class="details-content">
                        <div class="details-left">
                            <div class="detail-item"><i class="fa-solid fa-car"></i> ID Voyage : <?= $voyage->id ?></div>
                            <div class="detail-item"><i class="fa-solid fa-car-side"></i> <?= Html::encode($voyage->marqueVehicule->marquev) ?> – <?= Html::encode($voyage->typeVehicule->typev) ?></div>
                            <div class="detail-item"><i class="fa-solid fa-triangle-exclamation"></i> <?= Html::encode($voyage->contraintes ?: 'Aucune') ?></div>
                        </div>

                        <div class="details-right">
                            <div class="detail-item"><i class="fa-solid fa-user"></i> <strong><?= Html::encode($voyage->conducteurInfo->pseudo) ?></strong></div>
                            <div class="detail-item"><i class="fa-solid fa-phone"></i> 06 11 22 33 44</div>
                        </div>

                        <div class="reservation-box" id="res-box-<?= $voyage->id ?>">
                            
                            <?php if ($isGuest): ?>
                                <div class="alert-connexion" style="background: #fffbeb; border: 1px solid #fef3c7; padding: 12px; border-radius: 8px; color: #92400e; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; font-size: 0.9em;">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <span>Vous devez être connecté pour réserver un trajet.</span>
                                </div>
                            <?php endif; ?>

                            <div class="reservation-row">
                                <div>
                                    <label>Places à réserver</label>
                                    <input type="number" id="nb-places-<?= $voyage->id ?>" value="<?= $nbVoyageurs ?>" readonly>
                                </div>
                                <div>
                                    <label>Prix total</label>
                                    <input type="text" value="<?= number_format($data['cout_total'], 2) ?> €" readonly>
                                </div>
                            </div>

                            <button
                                class="btn-reserver"
                                data-voyage="<?= $voyage->id ?>"
                                data-places="<?= $nbVoyageurs ?>"
                                <?= (!$data['est_disponible'] || $isGuest) ? 'disabled style="background:#ccc; cursor:not-allowed;"' : '' ?>
                            >
                                RÉSERVER
                            </button>
                        </div>
                    </div>
                </details>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($withCorrespondances && !empty($correspondances)): ?>
            <div class="correspondance-header">
                <div class="correspondance-title">Correspondances disponibles</div>
                <div class="correspondance-subtitle">Trajets avec correspondances</div>
            </div>
            <div class="correspondance-list">
                <?php foreach ($correspondances as $index => $correspondance): ?>
                    <?php
                        $cities = $correspondance['cities'];
                        $segments = $correspondance['segments'];
                        $hasAllSegments = true;
                        $minTotal = 0.0;
                        $availableSegments = 0;
                        $chosenVoyages = [];
                        $chosenSegments = [];
                        $bestSegments = [];
                        $totalDistance = 0;
                        $minPlaces = null;
                        foreach ($segments as $segment) {
                            if (empty($segment['voyages'])) {
                                $hasAllSegments = false;
                                continue;
                            }
                            $totalDistance += (int)$segment['trajet']->distance;
                            $chosenVoyage = null;
                            foreach ($segment['voyages'] as $segmentData) {
                                if (!$segmentData['est_disponible']) {
                                    continue;
                                }
                                if ($chosenVoyage === null || $segmentData['cout_total'] < $chosenVoyage['cout_total']) {
                                    $chosenVoyage = $segmentData;
                                }
                            }
                            if ($chosenVoyage === null) {
                                $hasAllSegments = false;
                                continue;
                            }
                            $availableSegments++;
                            $minTotal += (float)$chosenVoyage['cout_total'];
                            $chosenVoyages[] = (int)$chosenVoyage['voyage']->id;
                            $chosenSegments[] = $chosenVoyage['voyage'];
                            $bestSegments[] = $chosenVoyage;
                            $minPlaces = $minPlaces === null
                                ? (int)$chosenVoyage['places_restantes']
                                : min($minPlaces, (int)$chosenVoyage['places_restantes']);
                        }
                        $canReserveAll = $hasAllSegments && count($chosenVoyages) === count($segments) && !$isGuest;

                        $startHour = $chosenSegments ? (int)$chosenSegments[0]->heuredepart : null;
                        $arrivalMinutes = $startHour !== null ? ($startHour * 60 + $totalDistance) : null;
                        $arrivalHour = $arrivalMinutes !== null ? (int)floor(($arrivalMinutes / 60) % 24) : null;
                        $arrivalMinute = $arrivalMinutes !== null ? (int)($arrivalMinutes % 60) : null;
                        $dureeHeures = (int)floor($totalDistance / 60);
                        $dureeMinutes = $totalDistance % 60;
                        $nbCorrespondances = (int)($correspondance['nb_correspondances'] ?? (count($segments) - 1));
                        $correspondanceCity = $cities[1] ?? null;
                    ?>
                    <div class="voyage-card correspondance-card <?= $hasAllSegments ? '' : 'correspondance-disabled' ?>">
                        <div class="voyage-line">
                            <div class="heure-ville">
                                <strong><?= $startHour !== null ? $startHour . 'h' : '--' ?></strong><br>
                                <small><?= Html::encode($cities[0]) ?></small>
                            </div>

                            <div class="trajet-ligne">
                                <span class="distance"><?= $totalDistance ?> km</span><br>
                                <span class="duree">
                                    Durée : <?= $totalDistance ? Html::encode($dureeHeures . 'h' . str_pad((string)$dureeMinutes, 2, '0', STR_PAD_LEFT)) : '-' ?>
                                </span>
                                <span class="correspondance-label">
                                    <?= $nbCorrespondances ?> correspondance(s)
                                </span>
                            </div>

                            <div class="heure-ville">
                                <strong>
                                    <?= $arrivalHour !== null ? Html::encode($arrivalHour . 'h' . str_pad((string)$arrivalMinute, 2, '0', STR_PAD_LEFT)) : '--' ?>
                                </strong><br>
                                <small><?= Html::encode($cities[count($cities) - 1]) ?></small>
                            </div>

                            <div class="prix">
                                <?= $hasAllSegments ? number_format($minTotal, 2) . ' €' : 'Prix indisponible' ?>
                            </div>
                        </div>

                        <div class="places-info">
                            <span class="place-item"><i class="fa-solid fa-route"></i> Segments : <?= count($segments) ?></span>
                            <span class="place-item"><i class="fa-solid fa-link"></i> Correspondances : <?= $nbCorrespondances ?></span>
                            <span class="place-item"><i class="fa-solid fa-check-circle"></i> Places min : <?= (int)$minPlaces ?></span>
                        </div>

                        <div class="voyage-status">
                            <span class="etat disponible">Disponible</span>
                        </div>

                        <details class="voyage-details correspondance-details">
                            <summary>Voir les détails</summary>
                            <div class="details-content correspondance-details-content">
                                <div class="details-left">
                                    <?php foreach ($segments as $segmentIndex => $segment): ?>
                                        <?php
                                            $bestSegment = $bestSegments[$segmentIndex] ?? null;
                                            $trajet = $segment['trajet'];
                                        ?>
                                        <?php if ($bestSegment): ?>
                                            <?php
                                                $segmentVoyage = $bestSegment['voyage'];
                                                $segmentDistance = $segmentVoyage->trajetInfo->distance;
                                                $segmentHeureDepart = (int)$segmentVoyage->heuredepart;
                                                $segmentArriveeMinutes = $segmentHeureDepart * 60 + $segmentDistance;
                                                $segmentHeureArrivee = (int)floor(($segmentArriveeMinutes / 60) % 24);
                                                $segmentMinuteArrivee = (int)($segmentArriveeMinutes % 60);
                                            ?>
                                            <div class="correspondance-step">
                                                <div class="step-title">Segment <?= $segmentIndex + 1 ?></div>
                                                <div class="step-line">
                                                    <?= Html::encode($trajet->depart) ?> → <?= Html::encode($trajet->arrivee) ?>
                                                </div>
                                                <div class="step-time">
                                                    Départ <?= $segmentHeureDepart ?>h · Arrivée <?= Html::encode($segmentHeureArrivee . 'h' . str_pad((string)$segmentMinuteArrivee, 2, '0', STR_PAD_LEFT)) ?>
                                                </div>
                                                <div class="step-price">
                                                    <?= number_format($bestSegment['cout_total'], 2) ?> €
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>

                                <div class="details-right">
                                    <div class="detail-item"><i class="fa-solid fa-coins"></i> Prix total : <?= $hasAllSegments ? number_format($minTotal, 2) . ' €' : 'Prix indisponible' ?></div>
                                    <div class="detail-item"><i class="fa-solid fa-chair"></i> Places restantes : <?= max(0, (int)$minPlaces - (int)$nbVoyageurs) ?></div>
                                    <?php if ($correspondanceCity): ?>
                                        <div class="detail-item"><i class="fa-solid fa-location-dot"></i> Correspondance à <?= Html::encode($correspondanceCity) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="reservation-box correspondance-reservation">
                                    <?php if ($isGuest): ?>
                                        <div class="alert-connexion">
                                            <i class="fa-solid fa-circle-info"></i>
                                            <span>Vous devez être connecté pour réserver un trajet.</span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="reservation-row">
                                        <div>
                                            <label>Places à réserver</label>
                                            <input type="number" value="<?= $nbVoyageurs ?>" readonly>
                                        </div>
                                        <div>
                                            <label>Prix total</label>
                                            <input type="text" value="<?= $hasAllSegments ? number_format($minTotal, 2) . ' €' : 'Prix indisponible' ?>" readonly>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn-reserver js-reserver-correspondance"
                                        data-url="<?= Html::encode(Url::to(['voyage/reserver-correspondance'])) ?>"
                                        data-voyages="<?= Html::encode(Json::encode($chosenVoyages)) ?>"
                                        data-places="<?= (int)$nbVoyageurs ?>"
                                        <?= $canReserveAll ? '' : 'disabled' ?>
                                    >
                                        Réserver les <?= count($segments) ?> étapes
                                    </button>
                                </div>
                            </div>
                        </details>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

<?php else: ?>
    <p class="info-init">Veuillez saisir vos critères pour lancer la recherche.</p>
<?php endif; ?>
