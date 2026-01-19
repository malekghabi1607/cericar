<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Modifier le profil';
$this->registerCssFile('@web/css/profil-edit.css');
$this->registerCssFile('@web/css/profil.css');
$this->registerJsFile(
    '@web/js/profil-edit.js',
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
?>

<div class="profile-edit-page">
    <div class="profile-edit-card search-form-bg">
        <h1>Modifier le profil</h1>

        <?php $form = ActiveForm::begin([
            'id' => 'profil-edit-form',
            'method' => 'post',
            'options' => [
                'enctype' => 'multipart/form-data',
                'data-profile-url' => Url::to(['site/profil']),
            ],
        ]); ?>

        <div class="form-grid">
            <?= $form->field($model, 'pseudo')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'mail')->input('email') ?>
            <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'prenom')->textInput(['maxlength' => true]) ?>
        </div>

        <?= $form->field($model, 'photo')->input('url', [
            'placeholder' => 'https://exemple.com/photo.jpg',
        ]) ?>
        <?= $form->field($model, 'photoFile')->fileInput() ?>

        <?= $form->field($model, 'permis')->textInput([
            'placeholder' => 'Numero de permis (12 chiffres)',
            'inputmode' => 'numeric',
            'pattern' => '\\d{12}',
            'maxlength' => 12,
        ]) ?>

        <div class="form-actions">
            <?= Html::submitButton('Enregistrer', ['class' => 'btn-save']) ?>
            <?= Html::a('Annuler', ['site/profil'], ['class' => 'btn-cancel']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
