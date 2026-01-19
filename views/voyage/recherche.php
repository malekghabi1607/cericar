<?php
use yii\helpers\Html;

$this->title = 'Recherche de trajet';
$this->registerCssFile('@web/css/recherche.css');
$this->registerJsFile(
    '@web/js/recherche.js',
    ['depends' => [\yii\web\JqueryAsset::class]]

);


?>

<?= Html::csrfMetaTags() ?>

<!-- ===== TITRE DE LA PAGE ===== -->
<div class="page-title">
    <h1>Recherche de trajet</h1>
    <p class="page-subtitle">
        Trouvez un trajet disponible selon votre ville de départ,
        d’arrivée et vos préférences.
    </p>
</div>

<!-- ============================= -->
<!-- SECTION 1 : BARRE DE RECHERCHE  -->
<!-- ============================= -->
<section class="search-box">

<?= Html::beginForm(
    ['voyage/recherche'],
    'get',
    ['class' => 'search-grid form-recherche']
) ?>

        <div class="input">
            <i class="fa-solid fa-location-dot"></i>
            <?= Html::textInput('depart', $vDepart, [
                'placeholder' => 'Ville de départ'
            ]) ?>

        </div>

        <div class="input">
            <i class="fa-solid fa-flag"></i>
           <?= Html::textInput('arrivee', $vArrivee, [
            'placeholder' => 'Ville d’arrivée'
            ]) 
           ?>
        </div>

    <!-- Date (affichée mais NON utilisée à l’étape 3) -->
    <div class="input">
        <i class="fa-solid fa-calendar"></i>
        <?= Html::input(
            'text',
            'date_affichage',          // nom volontairement différent (non utilisé)
            '',
            [
                'id' => 'dateInput',
                'readonly' => true,
                'placeholder' => 'Date'
            ]
        ) ?>
    </div>

    <!-- Nombre de voyageurs -->
    <div class="input">
        <i class="fa-solid fa-user-group"></i>
       <?= Html::input('number', 'nb_pers', $nbVoyageurs ?? 1, [
        'min' => 1
       ]) ?>
    </div>

    <div class="search-options">
        <label class="checkbox">
            <?= Html::checkbox('with_correspondances', !empty($withCorrespondances), ['value' => 1]) ?>
            <span>Accepter les correspondances</span>
        </label>
    </div>

    <button type="submit" class="btn-search btn-search-full">
        <i class="fa-solid fa-magnifying-glass"></i> Rechercher
    </button>

    <?= Html::endForm() ?>

</section>



<!-- ============================= -->
<!-- SECTION 3 : RÉSULTATS -->
<!-- ============================= -->
<div id="resultats-voyages" class="results-container">
    <?= $this->render('_resultats', [
        'resultats' => $resultats,
        'correspondances' => $correspondances ?? [],
        'withCorrespondances' => $withCorrespondances ?? false,
        'rechercheLancee' => $rechercheLancee,
        'vDepart' => $vDepart,
        'vArrivee' => $vArrivee,
        'nbVoyageurs' => $nbVoyageurs,
    ]) ?>
</div>
