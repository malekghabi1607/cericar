<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Proposer un voyage';
$this->registerCssFile('@web/css/voyage-create.css');
$this->registerCssFile('@web/css/profil.css');
$this->registerJsFile(
    '@web/js/voyage-create.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
    '@web/js/profil-actions.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
    '@web/js/profil-permis.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

$typeOptions = ArrayHelper::map($types, 'id', 'typev');
$marqueOptions = ArrayHelper::map($marques, 'id', 'marquev');
$trajetOptions = ArrayHelper::map($trajets, 'id', function ($trajet) {
    return $trajet->depart . ' -> ' . $trajet->arrivee . ' (' . $trajet->distance . ' km)';
});
?>

<div class="voyage-create-page">
    <div class="page-title">
        <h1>Proposer un nouveau voyage</h1>
        <p class="page-subtitle">Completez les informations du trajet.</p>
    </div>

    <div class="voyage-form-card search-form-bg">
        <?php $form = ActiveForm::begin([
            'id' => 'voyage-create-form',
            'method' => 'post',
            'options' => [
                'data-profile-url' => Url::to(['site/profil']),
            ],
        ]); ?>

        <div class="form-grid">
            <?= $form->field($voyage, 'trajet')->dropDownList($trajetOptions, ['prompt' => 'Selectionnez votre itineraire...']) ?>
        </div>

        <div class="form-grid">
            <?= $form->field($voyage, 'heuredepart')->input('number', ['min' => 0, 'max' => 23]) ?>
            <?= $form->field($voyage, 'nbplacedispo')->input('number', ['min' => 1]) ?>
            <?= $form->field($voyage, 'tarif')->input('number', ['step' => '0.01', 'min' => 0, 'placeholder' => 'Ex: 0.05']) ?>
            <?= $form->field($voyage, 'nbbagage')->input('number', ['min' => 0]) ?>
            <?= $form->field($voyage, 'idtypev')->dropDownList($typeOptions, ['prompt' => 'Selectionnez le type...']) ?>
            <?= $form->field($voyage, 'idmarquev')->dropDownList($marqueOptions, ['prompt' => 'Selectionnez la marque...']) ?>
        </div>

        <?= $form->field($voyage, 'contraintes')->textarea(['rows' => 4, 'placeholder' => 'Ex: Non-fumeur, animaux autorises...']) ?>

        <div class="form-actions">
            <?= Html::submitButton("Publier l'annonce", ['class' => 'btn-save']) ?>
            <?= Html::a('Annuler', ['site/profil'], ['class' => 'btn-cancel']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
