<?php
use yii\helpers\Html;

$this->title = 'Reservation';
$this->registerCssFile('@web/css/reservation.css');

$voyage = $reservation->voyageInfo;
$trajet = $voyage ? ($voyage->trajetInfo ?? null) : null;
$conducteur = $voyage ? ($voyage->conducteurInfo ?? null) : null;
$distance = $trajet ? (int)$trajet->distance : 0;
$dureeHeures = (int)floor($distance / 60);
$dureeMinutes = $distance % 60;
$heureDepart = $voyage ? (int)$voyage->heuredepart : null;
$minutesArrivee = $heureDepart !== null ? ($heureDepart * 60 + $distance) : null;
$heureArrivee = $minutesArrivee !== null ? (int)floor(($minutesArrivee / 60) % 24) : null;
$minuteArrivee = $minutesArrivee !== null ? (int)($minutesArrivee % 60) : null;
$tarifTotal = ($voyage && $distance) ? ((float)$voyage->tarif * $distance * (int)$reservation->nbplaceresa) : 0;
$isCancelled = $voyage && $voyage->contraintes && stripos($voyage->contraintes, 'ANNULE') !== false;
$placesRestantes = 0;
$nbReservations = 0;
if ($voyage) {
    $placesReservees = \app\models\Reservation::find()
        ->where(['voyage' => $voyage->id])
        ->sum('nbplaceresa') ?? 0;
    $placesRestantes = max(0, (int)$voyage->nbplacedispo - (int)$placesReservees);
    $nbReservations = \app\models\Reservation::find()
        ->where(['voyage' => $voyage->id])
        ->count();
}
?>

<div class="reservation-page">
    <div class="reservation-card">
        <div class="reservation-top">
            <div class="city-block">
                <div class="time">
                    <?= $heureDepart !== null ? Html::encode($heureDepart . 'h') : '--' ?>
                </div>
                <div class="city"><?= Html::encode($trajet->depart ?? 'Depart') ?></div>
            </div>

            <div class="trip-meta">
                <div class="distance"><?= $distance ? Html::encode($distance) . ' km' : '-' ?></div>
                <div class="duration">
                    Duree : <?= $distance ? Html::encode($dureeHeures . 'h' . str_pad((string)$dureeMinutes, 2, '0', STR_PAD_LEFT)) : '-' ?>
                </div>
            </div>

            <div class="city-block align-right">
                <div class="time">
                    <?= $heureArrivee !== null ? Html::encode($heureArrivee . 'h' . str_pad((string)$minuteArrivee, 2, '0', STR_PAD_LEFT)) : '--' ?>
                </div>
                <div class="city"><?= Html::encode($trajet->arrivee ?? 'Arrivee') ?></div>
            </div>

            <div class="price-pill">
                <?= number_format($tarifTotal, 2) ?> €
            </div>
        </div>

        <div class="reservation-badges">
            <div class="badge-item">
                <i class="fa-solid fa-layer-group"></i>
                Nb reservations : <?= (int)$nbReservations ?>
            </div>
            <div class="badge-item">
                <i class="fa-solid fa-circle-check"></i>
                Restantes : <?= (int)$placesRestantes ?>
            </div>
            <?php if ($isCancelled): ?>
                <div class="badge-item">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Trajet annule par le conducteur
                </div>
            <?php endif; ?>
        </div>

        <div class="reservation-section">
            <div class="details-grid">
                <div class="detail-item">
                    <i class="fa-solid fa-car"></i>
                    ID Voyage : <?= Html::encode($voyage ? $voyage->id : '-') ?>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-user"></i>
                    <?= Html::encode($conducteur ? ($conducteur->prenom . ' ' . $conducteur->nom) : 'Inconnu') ?>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-car-side"></i>
                    <?= Html::encode($voyage->marqueVehicule->marquev ?? 'Inconnu') ?>
                    <?= Html::encode($voyage->typeVehicule->typev ?? '') ?>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-phone"></i>
                    06 11 22 33 44
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <?= Html::encode($voyage->contraintes ?: 'Aucune') ?>
                </div>
            </div>
        </div>

        <div class="reservation-actions">
            <?= Html::a('Retour au profil', ['site/profil'], ['class' => 'btn-back']) ?>
        </div>
    </div>
</div>
