<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
$this->registerCssFile('@web/css/site.css?v=1');
$this->title = 'Connexion';

?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Veuillez saisir vos identifiants :</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput([
                'autofocus' => true,
                'placeholder' => 'Ex. Loup',
            ])->label('Identifiant') ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => 'Mot de passe',
            ])->label('Mot de passe') ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"form-check\">{input} {label}</div>\n{error}",
                'labelOptions' => ['class' => 'form-check-label'],
                'checkboxOptions' => ['class' => 'form-check-input'],
            ])->label('Se souvenir de moi') ?>

            <div class="form-group mt-3">
                <?= Html::submitButton('Se connecter', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <div class="text-muted mt-3" style="font-size:0.95rem">
                Comptes de test : <strong>Loup / test</strong>, <strong>Requin / test</strong>…<br>
                (La recherche d’identifiant est insensible à la casse.)
            </div>

        </div>
    </div>
</div>