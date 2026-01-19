<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Permis requis';
?>

<div class="permit-required-page">
    <div class="permit-required-card">
        <h1>Permis requis</h1>
        <p>
            Vous devez enregistrer un numero de permis pour proposer un voyage.
        </p>
        <?= Html::a(
            'Aller au profil',
            Url::to(['site/profil', '#' => 'permit-section']),
            ['class' => 'btn-proposer']
        ) ?>
    </div>
</div>
