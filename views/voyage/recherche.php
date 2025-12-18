<?php
/* @var $this yii\web.View */
/* @var $resultats array */
/* @var $rechercheLancee boolean */
/* @var $vDepart string */
/* @var $vArrivee string */
/* @var $nbVoyageurs int */

use yii\helpers\Html;

$this->title = 'Recherche de Covoiturage';

// Chargement du fichier CSS spécifique à cette vue
$this->registerCssFile('@web/css/recherche.css');

?>

<div class="container mt-5" style="max-width: 700px;">

    <div class="card shadow-lg p-4" style="border-radius: 14px;">
        
        <h2 class="text-center mb-4" style="font-weight: 700;">
            Rechercher votre Covoiturage
        </h2>
        <!-- génère un formulaire -->
        <?= Html::beginForm(['voyage/recherche'], 'get', ['class' => 'form-recherche']) ?>

            <div class="form-group mb-3">
                <?= Html::label('Départ', 'depart', ['class' => 'fw-bold']) ?>
                <?= Html::textInput(
                        'depart',
                        $vDepart,
                        [
                            'class' => 'form-control form-control-lg',
                            'placeholder' => 'Ex : Toulouse',
                            'required' => true
                        ]
                ) ?>
            </div>

            <div class="form-group mb-3">
                <?= Html::label('Arrivée', 'arrivee', ['class' => 'fw-bold']) ?>
                <?= Html::textInput(
                        'arrivee',
                        $vArrivee,
                        [
                            'class' => 'form-control form-control-lg',
                            'placeholder' => 'Ex : Marseille',
                            'required' => true
                        ]
                ) ?>
            </div>

            <div class="form-group mb-4">
                <?= Html::label('Nombre de voyageurs', 'nb_pers', ['class' => 'fw-bold']) ?>
                <?= Html::input(
                        'number',
                        'nb_pers',
                        $nbVoyageurs,
                        [
                            'class' => 'form-control form-control-lg',
                            'min' => 1,
                            'placeholder' => 'Ex : 3',
                            'required' => true
                        ]
                ) ?>
            </div>

            <?= Html::submitButton(
                'Rechercher',
                [
                    'class' => 'btn btn-primary btn-lg w-100',
                    'style' => 'font-size: 18px; font-weight:600;'
                ]
            ) ?>

        <?= Html::endForm() ?>
    </div>

    <div class="mt-4" id="resultats-voyages">
        <?= $this->render('_resultats', [
            'resultats' => $resultats,
            'rechercheLancee' => $rechercheLancee,
            'vDepart' => $vDepart,
            'vArrivee' => $vArrivee,
            'nbVoyageurs' => $nbVoyageurs,
        ]) ?>
    </div>

</div>
