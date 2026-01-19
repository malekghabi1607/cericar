<?php
use yii\helpers\Html;

$this->title = 'Inscription réussie';
$this->registerCssFile('@web/css/register-success.css');
?>

<div class="success-page">
    <div class="success-card">

        <div class="success-icon">✔</div>

        <h1>Inscription réussie</h1>

        <p>
            Votre compte a été créé avec succès.<br>
            Vous pouvez maintenant vous connecter.
        </p>

        <?= Html::a(
            'Se connecter',
            ['site/login'],
            ['class' => 'btn-success']
        ) ?>

    </div>
</div>
