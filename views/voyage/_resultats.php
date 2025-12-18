<?php
use yii\helpers\Html;

/* @var $resultats array */
/* @var $rechercheLancee boolean */
/* @var $vDepart string */
/* @var $vArrivee string */
/* @var $nbVoyageurs int */
?>
<?php if ($rechercheLancee): ?>
   <!--  affiche le contexte de la recherche effectuée-->
    <h3 class="result-title">
        Résultats pour <?= Html::encode($vDepart) ?> → <?= Html::encode($vArrivee) ?>
        (<?= Html::encode($nbVoyageurs) ?> pers.)
    </h3>
    
    <?php if (empty($resultats)): ?>
         <!-- Aucun voyage trouvé -->
        <div class="voyage-card no-result">
            Aucun voyage direct trouvé pour ce trajet ou cette disponibilité.
        </div>

    <?php else: ?>
         <!-- Boucle sur chaque voyage trouvé -->
        <?php foreach ($resultats as $data): ?>
            <!-- Raccourci pour accéder à l'objet voyage-->
            <?php $voyage = $data['voyage']; ?>

            <?php 
            $cssClass = $data['est_complet']
                ? 'complet'
                : ($data['est_disponible'] ? 'disponible' : 'partiel');
            ?>

            <div class="voyage-card <?= $cssClass ?>">

                <h4 class="voyage-title">
                    <?= Html::encode($voyage->trajetInfo->depart) ?>
                    
                    <?= Html::encode($voyage->trajetInfo->arrivee) ?>
                </h4>

                <div class="voyage-info">
                    <p><strong>Voyage ID :</strong> <?= $voyage->id ?></p>
                    <p><strong>Heure de départ :</strong> <?= $voyage->heuredepart ?>h</p>
                    <p><strong>Places demandées :</strong> <?= Html::encode($nbVoyageurs) ?></p>
                    <p><strong>Places restantes :</strong> <b><?= $data['places_restantes'] ?></b></p>
                </div>

                <div class="voyage-status">
                    <?php if ($data['est_complet']): ?>

                        <p class="etat complet">COMPLET – Impossible de réserver</p>

                    <?php elseif ($data['pasAssezPourDemande']): ?>

                        <p class="etat manque">Pas assez de places – Impossible de réserver</p>

                    <?php elseif ($data['est_disponible']): ?>

                        <p class="etat disponible">
                            DISPONIBLE – Coût estimé :
                            <?= number_format($data['cout_total'], 2) ?> €
                        </p>

                        <?php if (Yii::$app->session->has('user')): ?>

                            <?= Html::a(
                                'Réserver',
                                ['voyage/reserver', 'idVoyage' => $voyage->id, 'nbPlaces' => $nbVoyageurs],
                                ['class' => 'btn btn-success btn-sm btn-reserver']
                            ) ?>

                        <?php else: ?>

                            <?= Html::a(
                                'Connexion requise',
                                ['auth/login'],
                                ['class' => 'btn btn-warning btn-sm']
                            ) ?>

                        <?php endif; ?>

                    <?php endif; ?>
                </div>

                <details class="voyage-details">
                    <summary>Détails (Conducteur, Véhicule)</summary>
                    <div class="details-content">
                        <p><strong>Conducteur :</strong> <?= Html::encode($voyage->conducteurInfo->pseudo) ?></p>
                        <p><strong>Véhicule :</strong>
                            <?= Html::encode($voyage->marqueVehicule->marquev) ?>
                            <?= Html::encode($voyage->typeVehicule->typev) ?>
                        </p>
                        <p><strong>Contraintes :</strong>
                            <?= Html::encode($voyage->contraintes) ?: 'Aucune contrainte spécifique.' ?>
                        </p>
                    </div>
                </details>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

<?php else: ?>

    <p class="info-init">Veuillez saisir vos critères pour lancer la recherche.</p>

<?php endif; ?>
