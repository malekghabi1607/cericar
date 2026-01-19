<?php
use yii\helpers\Html;

$this->title = 'Connexion réussie';
$this->registerCssFile('@web/css/register-success.css');

$internaute = Yii::$app->session->get('internaute');
?>

<div class="success-page">
    <div class="success-card">

        <div class="success-icon">✔</div>

        <h1>Connexion réussie</h1>

        <p>
            Bienvenue <strong><?= Html::encode($internaute['pseudo']) ?></strong> 👋<br>
            Vous êtes maintenant connecté.
        </p>

        <?= Html::a(
            'Aller à l’accueil',
            ['site/index'],
            ['class' => 'btn-success']
        ) ?>

    </div>
</div>
