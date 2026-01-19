<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Mon Profil';
$this->registerCssFile('@web/css/profil.css');
$this->registerJsFile(
    '@web/js/profil-permis.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
    '@web/js/profil-actions.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

$prenom = (string)($user->prenom ?? '');
$nom = (string)($user->nom ?? '');
$initials = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
if ($initials === '') {
    $initials = strtoupper(substr((string)$user->pseudo, 0, 2));
}
$photoFile = trim((string)($user->photo ?? ''));
$photoUrl = '';
if ($photoFile !== '') {
    if (preg_match('/^https?:\/\//i', $photoFile)) {
        $photoUrl = $photoFile;
    } else {
        $fileName = basename($photoFile);
        $filePath = Yii::getAlias('@runtime/avatars/' . $fileName);
        if (is_file($filePath)) {
            $photoUrl = Url::to(['site/avatar', 'id' => $user->id]);
        }
    }
}
?>

<div class="profile-page">
    <h1 class="profile-title">Mon Profil</h1>

    <section class="profile-card profile-header">
        <div class="avatar-circle">
            <?php if ($photoUrl !== ''): ?>
                <?= Html::img($photoUrl, ['alt' => 'Photo de profil']) ?>
            <?php else: ?>
                <span class="avatar-initials"><?= Html::encode($initials) ?></span>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <div class="welcome-row">
                <div class="welcome">
                    Bienvenue, <span><?= Html::encode($user->pseudo) ?></span> !
                </div>
                <div class="info-actions">
                <?= Html::a(
                    '<i class="fa-solid fa-pen"></i>',
                    ['site/profil-edit'],
                    ['class' => 'btn-edit-icon', 'title' => 'Modifier le profil', 'aria-label' => 'Modifier le profil']
                ) ?>
                </div>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="label">Id</div>
                    <div class="value"><?= (int)$user->id ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Pseudo</div>
                    <div class="value"><?= Html::encode($user->pseudo) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Nom</div>
                    <div class="value"><?= Html::encode($user->nom) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Prenom</div>
                    <div class="value"><?= Html::encode($user->prenom) ?></div>
                </div>
            </div>
        </div>
    </section>

    <section class="profile-card">
        <h2 class="section-title">
            Vous avez actuellement <?= (int)$totalReservations ?> reservation(s) en cours :
        </h2>

        <?php if (empty($reservations)): ?>
            <div class="empty-state">Aucune reservation pour le moment.</div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="profile-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Reference du voyage</th>
                            <th>Conducteur du voyage</th>
                            <th>Trajet</th>
                            <th>Durée</th>
                            <th>Places reservées</th>
                            <th>Tarif total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $index => $data): ?>
                            <?php
                                $voyage = $data['voyage'];
                                $trajet = $data['trajet'];
                                $conducteur = $data['conducteur'];
                                $isCancelled = $voyage && $voyage->contraintes && stripos($voyage->contraintes, 'ANNULE') !== false;
                                $distance = $trajet ? (int)$trajet->distance : 0;
                                $tarifTotal = $voyage ? ((float)$voyage->tarif * $distance * (int)$data['places_reservees']) : 0;
                                $conducteurInitials = '';
                                if ($conducteur) {
                                    $conducteurInitials = strtoupper(
                                        substr((string)$conducteur->prenom, 0, 1) .
                                        substr((string)$conducteur->nom, 0, 1)
                                    );
                                }
                            ?>
                            <tr class="<?= $isCancelled ? 'is-cancelled' : '' ?>">
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <div class="muted">Voyage : #<?= Html::encode($voyage ? $voyage->id : '-') ?></div>
                                    <div class="muted">Trajet : #<?= Html::encode($trajet->id ?? '-') ?></div>
                                </td>
                                <td>
                                    <div class="inline-user">
                                        <span class="mini-avatar"><?= Html::encode($conducteurInitials ?: '--') ?></span>
                                        <span><?= Html::encode($conducteur ? ($conducteur->prenom . ' ' . $conducteur->nom) : 'Inconnu') ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?= Html::encode(($trajet->depart ?? 'Inconnu') . ' -> ' . ($trajet->arrivee ?? 'Inconnu')) ?><br>
                                    <span class="muted"><?= Html::encode($trajet->distance ?? '-') ?> km</span>
                                </td>
                                <td>
                                    <?php
                                        $dureeHeures = (int)floor($distance / 60);
                                        $dureeMinutes = $distance % 60;
                                    ?>
                                    <?= $distance ? Html::encode($dureeHeures . 'h' . str_pad((string)$dureeMinutes, 2, '0', STR_PAD_LEFT)) : '-' ?>
                                    <br>
                                    <span class="muted">Duree estimee</span>
                                </td>
                                <td><?= (int)$data['places_reservees'] ?></td>
                                <td class="price">
                                    <?= number_format($tarifTotal, 2) ?> €
                                    <?php if ($voyage && $voyage->contraintes && stripos($voyage->contraintes, 'ANNULE') !== false): ?>
                                        <div class="muted">Trajet annule par le conducteur</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <?= Html::a(
                                            '<i class="fa-solid fa-eye"></i>',
                                            ['reservation/view', 'id' => $data['reservation_ids'][0] ?? null],
                                            ['class' => 'icon-btn', 'title' => 'Voir', 'aria-label' => 'Voir']
                                        ) ?>
                                        <?= Html::a(
                                            '<i class="fa-solid fa-trash"></i>',
                                            ['reservation/delete', 'id' => $data['reservation_ids'][0] ?? null],
                                            [
                                                'class' => 'icon-btn danger js-ajax-link',
                                                'title' => $isCancelled ? 'Supprimer definitivement' : 'Supprimer',
                                                'aria-label' => 'Supprimer',
                                                'data-method' => 'post',
                                                'data-confirm' => $isCancelled ? 'Supprimer definitivement cette reservation ?' : 'Supprimer cette reservation ?'
                                            ]
                                        ) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="profile-card">
        <h2 class="section-title">
            Vous avez actuellement <?= count($mesPropositions) ?> voyage(s) propose(s) :
        </h2>

        <?php if (empty($mesPropositions)): ?>
            <div class="empty-state">Vous n'avez pas encore propose de voyages.</div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="profile-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Trajet</th>
                            <th>Durée</th>
                            <th>Vehicule</th>
                            <th>Places</th>
                            <th>Tarif total</th>
                            <th>Contraintes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mesPropositions as $index => $voyage): ?>
                            <?php $trajet = $voyage->trajetInfo ?? null; ?>
                            <?php $isCancelled = $voyage->contraintes && stripos($voyage->contraintes, 'ANNULE') !== false; ?>
                            <?php
                                $contraintesLabel = $voyage->contraintes ?: 'Aucune';
                                if ($isCancelled) {
                                    $contraintesLabel = trim(preg_replace('/^ANNULE PAR LE CONDUCTEUR\.\s*/i', '', (string)$contraintesLabel));
                                    if ($contraintesLabel === '') {
                                        $contraintesLabel = 'Aucune';
                                    }
                                }
                            ?>
                            <tr class="<?= $isCancelled ? 'is-cancelled' : '' ?>">
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <?= Html::encode(($trajet->depart ?? 'Inconnu') . ' -> ' . ($trajet->arrivee ?? 'Inconnu')) ?><br>
                                    <span class="muted"><?= Html::encode($trajet->distance ?? '-') ?> km</span>
                                    <?php if ($isCancelled): ?>
                                        <div class="muted">Trajet annule par vous</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $distance = $trajet ? (int)$trajet->distance : 0;
                                        $dureeHeures = (int)floor($distance / 60);
                                        $dureeMinutes = $distance % 60;
                                    ?>
                                    <?= $distance ? Html::encode($dureeHeures . 'h' . str_pad((string)$dureeMinutes, 2, '0', STR_PAD_LEFT)) : '-' ?>
                                    <br>
                                    <span class="muted">Duree estimee</span>
                                </td>
                                <td>
                                    <?= Html::encode($voyage->marqueVehicule->marquev ?? 'Inconnu') ?><br>
                                    <span class="muted"><?= Html::encode($voyage->typeVehicule->typev ?? '') ?></span>
                                </td>
                                <td><?= (int)$voyage->nbplacedispo ?></td>
                                <?php $distance = $trajet ? (int)$trajet->distance : 0; ?>
                                <td class="price">
                                    <?= number_format((float)$voyage->tarif * $distance, 2) ?> €
                                </td>
                                <td><?= Html::encode($contraintesLabel) ?></td>
                                <td>
                                    <div class="actions">
                                        <?php if ($isCancelled): ?>
                                            <?= Html::a(
                                                '<i class="fa-solid fa-rotate-right"></i>',
                                                ['voyage/reactivate', 'id' => $voyage->id],
                                                [
                                                    'class' => 'btn-reactivate js-ajax-link',
                                                    'title' => 'Revalider',
                                                    'aria-label' => 'Revalider',
                                                    'data-method' => 'post',
                                                    'data-confirm' => 'Revalider ce voyage ?'
                                                ]
                                            ) ?>
                                            <?= Html::a(
                                                '<i class="fa-solid fa-trash"></i>',
                                                ['voyage/force-delete', 'id' => $voyage->id],
                                                [
                                                    'class' => 'btn-delete js-ajax-link',
                                                    'title' => 'Supprimer definitivement',
                                                    'aria-label' => 'Supprimer definitivement',
                                                    'data-method' => 'post',
                                                    'data-confirm' => 'Supprimer definitivement ce voyage et ses reservations ?'
                                                ]
                                            ) ?>
                                        <?php else: ?>
                                            <?= Html::a(
                                                '<i class="fa-solid fa-pen"></i>',
                                                ['voyage/update', 'id' => $voyage->id],
                                                ['class' => 'icon-btn', 'title' => 'Editer', 'aria-label' => 'Editer']
                                            ) ?>
                                            <?= Html::a(
                                                '<i class="fa-solid fa-trash"></i>',
                                                ['voyage/delete', 'id' => $voyage->id],
                                                [
                                                    'class' => 'icon-btn danger js-ajax-link',
                                                    'title' => 'Supprimer',
                                                    'aria-label' => 'Supprimer',
                                                    'data-method' => 'post',
                                                    'data-confirm' => 'Supprimer ce voyage ?'
                                                ]
                                            ) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="profile-card" id="permit-section">
        <h2 class="section-title">Devenir Conducteur</h2>
        <?php if (empty($user->permis)): ?>
            <p class="muted">Saisissez votre numero de permis pour proposer des voyages :</p>
            <?= Html::beginForm(['site/save-permis'], 'post', ['class' => 'permit-row search-form-bg', 'id' => 'permit-form']) ?>
                <?= Html::textInput('permis', '', [
                    'id' => 'num-permis',
                    'class' => 'permit-input',
                    'placeholder' => 'Numero de permis (12 chiffres)',
                    'inputmode' => 'numeric',
                    'pattern' => '\\d{12}',
                    'maxlength' => 12,
                    'required' => true,
                ]) ?>
                <?= Html::submitButton('Enregistrer', ['class' => 'permit-btn', 'id' => 'save-permis']) ?>
            <?= Html::endForm() ?>
        <?php else: ?>
            <div class="permit-success">
                Permis enregistre : <strong><?= Html::encode($user->permis) ?></strong>.
                Vous pouvez desormais proposer des voyages !
            </div>
        <?php endif; ?>
    </section>

    <?php if ($user->permis): ?>
        <div class="profile-cta">
            <?= Html::a(
                '<i class="fa-solid fa-plus"></i> Proposer un voyage',
                ['voyage/create'],
                ['class' => 'btn-proposer', 'id' => 'btn-proposer', 'encode' => false]
            ) ?>
        </div>
    <?php endif; ?>
</div>
