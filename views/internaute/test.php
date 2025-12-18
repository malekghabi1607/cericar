<?php
/**
 * Vue de test pour l'étape 2 - Version Finale (Sans ID)
 */

// Si erreur
if (isset($error)) {
    echo "<h3 style='color:red'>$error</h3>";
    return;
}

// Styles CSS minimes
$styleTable = "width: 100%; border-collapse: collapse; margin-bottom: 20px;";
$styleTh = "border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2; text-align: left;";
$styleTd = "border: 1px solid #ddd; padding: 8px;";
?>

<h1 style="border-bottom: 2px solid #333; padding-bottom: 10px;">
    Profil de <?= $internaute->pseudo ?>
</h1>

<div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
    <?php if (!empty($internaute->photo)): ?>
        <img src="<?= $internaute->photo ?>" alt="Avatar" style="width:100px; height:100px; border-radius:50%; object-fit:cover; border: 1px solid #ccc;">
    <?php endif; ?>
    
    <ul style="list-style: none; padding: 0;">
        <li><strong>Nom complet :</strong> <?= $internaute->prenom ?> <?= $internaute->nom ?></li>
        <li><strong>Email :</strong> <?= $internaute->mail ?></li>
        <li><strong>Permis :</strong> <?= $internaute->permis ? $internaute->permis : 'Non renseigné' ?></li>
    </ul>
</div>

<h2 style="color: #0056b3;">
    Voyages proposés (Conducteur)
</h2>

<?php $nbVoyages = count($voyages); ?>
<p><strong>Total : <?= $nbVoyages ?> voyage(s) proposé(s)</strong></p>

<?php if ($nbVoyages == 0): ?>
    <p><em>Aucun voyage proposé.</em></p>
<?php else: ?>
    <table style="<?= $styleTable ?>">
        <thead>
            <tr>
                <th style="<?= $styleTh ?>">Trajet (Départ ➝ Arrivée)</th>
                <th style="<?= $styleTh ?>">Détails Véhicule</th>
                <th style="<?= $styleTh ?>">Infos Voyage</th>
                <th style="<?= $styleTh ?>">Contraintes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($voyages as $v): ?>
                <tr>
                    <td style="<?= $styleTd ?>">
                        <?= $v->trajet0->depart ?> ➝ <?= $v->trajet0->arrivee ?><br>
                        <small>(<?= $v->trajet0->distance ?> km)</small>
                    </td>
                    <td style="<?= $styleTd ?>">
                        <?= $v->marquevehicule->marquev ?><br>
                        <?= $v->typevehicule->typev ?>
                    </td>
                    <td style="<?= $styleTd ?>">
                        <strong>Départ :</strong> <?= $v->heuredepart ?>h00<br>
                        <strong>Places :</strong> <?= $v->nbplacedispo ?><br>
                        <strong>tarif :</strong> <?= $v->tarif ?><br>
                        <strong>Prix Total :</strong> <?= number_format($v->getPrixTotal(), 2) ?> €
                    </td>
                    <td style="<?= $styleTd ?>">
                        <?= $v->contraintes ? $v->contraintes : '-' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h2 style="color: #0056b3;">
    Réservations (Passager)
</h2>

<?php $nbReservations = count($reservations); ?>
<p><strong>Total : <?= $nbReservations ?> réservation(s)</strong></p>

<?php if ($nbReservations == 0): ?>
    <p><em>Aucune réservation.</em></p>
<?php else: ?>
    <table style="<?= $styleTable ?>">
        <thead>
            <tr>
                <th style="<?= $styleTh ?>">Voyage Concerné</th>
                <th style="<?= $styleTh ?>">Places Réservées</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $r): ?>
                <tr>
                    <td style="<?= $styleTd ?>">
                        <?= $r->voyage0->trajet0->depart ?> ➝ <?= $r->voyage0->trajet0->arrivee ?><br>
                        <small>Conducteur : <?= $r->voyage0->conducteur0->pseudo ?></small>
                    </td>
                    <td style="<?= $styleTd ?>">
                        <?= $r->nbplaceresa ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>