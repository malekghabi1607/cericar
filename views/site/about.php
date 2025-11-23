<?php
/** @var yii\web\View $this */
use yii\helpers\Html;
$this->registerCssFile('@web/css/site.css?v=1');
$this->title = 'À propos';

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="intro">
        Bienvenue sur notre application développée avec le framework <strong>Yii2</strong>.
        <br><br>
        Ce projet a été réalisé dans le cadre du module <strong>Architecture Web</strong> 
        à l’Université d’Avignon.
    </p>

    <div class="about-content">
        <h3>🎯 Objectifs du projet</h3>
        <ul>
            <li>Découvrir et comprendre le fonctionnement du framework Yii2.</li>
            <li>Appliquer le modèle MVC (Modèle – Vue – Contrôleur).</li>
            <li>Connecter une application à une base de données PostgreSQL.</li>
            <li>Mettre en place un système d’authentification complet.</li>
        </ul>

        <h3>💻 Technologies utilisées</h3>
        <ul>
            <li><strong>Langage :</strong> PHP 8 / HTML5 / CSS3 / Bootstrap 5</li>
            <li><strong>Framework :</strong> Yii2</li>
            <li><strong>Base de données :</strong> PostgreSQL</li>
            <li><strong>Serveur :</strong> Apache sur pedago01c.univ-avignon.fr</li>
        </ul>

        <h3>👩‍💻 Auteure du projet</h3>
        <p><strong>Malek GHABI</strong><br>
        Étudiante en L3  informatique  — Université d’Avignon</p>
    </div>

</div>
