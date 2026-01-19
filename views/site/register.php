<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Créer un compte';
$this->registerCssFile('@web/css/register.css');
$this->registerJsFile(
    '@web/js/register.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>

<div class="register-page">

    <div class="register-card search-form-bg">
        <h1>Créer un compte</h1>
        <p class="subtitle">
            Inscrivez-vous pour réserver ou proposer des trajets.
        </p>

        <?php $form = ActiveForm::begin([
            'id' => 'register-form',
            'method' => 'post',
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <!-- PSEUDO -->
        <?= $form->field($model, 'pseudo')
            ->textInput(['placeholder' => 'Ex: malek_ghabi'])
            ->label('Pseudo *') ?>

        <!-- NOM / PRENOM -->
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'nom')
                    ->textInput(['placeholder' => 'Nom'])
                    ->label('Nom *') ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'prenom')
                    ->textInput(['placeholder' => 'Prénom'])
                    ->label('Prénom *') ?>
            </div>
        </div>

        <!-- EMAIL -->
        <?= $form->field($model, 'mail')
            ->input('email', ['placeholder' => 'email@exemple.com'])
            ->label('Email *') ?>

        <!-- PHOTO -->
        <?= $form->field($model, 'photo')
            ->input('url', ['placeholder' => 'https://exemple.com/photo.jpg'])
            ->label('URL photo (optionnel)') ?>
        <?= $form->field($model, 'photoFile')
            ->fileInput()
            ->label('Ou envoyer un fichier') ?>

        <!-- PERMIS -->
        <?= $form->field($model, 'permis')
            ->textInput([
                'placeholder' => 'Numéro de permis (12 chiffres)',
                'inputmode' => 'numeric',
                'pattern' => '\\d{12}',
                'maxlength' => 12,
            ])
            ->label('Permis de conduire') ?>

        <!-- MOT DE PASSE -->
        <?= $form->field($model, 'pass')
            ->passwordInput(['placeholder' => 'Mot de passe'])
            ->label('Mot de passe *') ?>

        <?= $form->field($model, 'password_repeat')
            ->passwordInput(['placeholder' => 'Confirmer le mot de passe'])
            ->label('Confirmation du mot de passe *') ?>

        <!-- BOUTON -->
        <div class="form-actions">
            <?= Html::submitButton('Créer mon compte', ['class' => 'btn-register']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="login-link">
            Déjà un compte ?
            <?= Html::a('Se connecter', ['site/login']) ?>
        </div>
    </div>

</div>
