<?php
use yii\helpers\Html;

$this->title = 'Connexion';
$this->registerCssFile('@web/css/login.css');
?>

<div class="login-page">

    <div class="login-card search-form-bg">

        <h1>Connexion</h1>
        <p class="subtitle">Accédez à votre espace personnel</p>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?= Html::encode($error) ?>
            </div>
        <?php endif; ?>

        <?= Html::beginForm(['site/login'], 'post', ['class' => 'login-form']) ?>

            <!-- IDENTIFIANT -->
            <div class="form-group">
                <label>Identifiant</label>
                <input
                    type="text"
                    name="pseudo"
                    placeholder="Votre pseudo"
                    required
                >
            </div>

            <!-- MOT DE PASSE -->
            <div class="form-group">
                <label>Mot de passe</label>
                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required
                >
            </div>

            <!-- BOUTON -->
            <button type="submit" class="btn-login">
                Se connecter
            </button>

        <?= Html::endForm() ?>

        <p class="register-link">
            Pas de compte ?
            <a href="<?= Yii::$app->urlManager->createUrl(['site/register']) ?>">
                Créez-en un
            </a>
        </p>

    </div>

</div>
