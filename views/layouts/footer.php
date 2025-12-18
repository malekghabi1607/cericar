<?php
use yii\helpers\Url;
?>

<footer class="footer-section">

    <div class="footer-container">

        <!-- COLONNE GAUCHE : Logo + réseaux sociaux -->
        <div class="footer-left">

            <!-- Réseaux sociaux -->
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>

            <!-- Logo -->
            <img src="<?= Yii::getAlias('@web/img/logo_transparent.png') ?>" class="footer-logo" alt="Logo CERIcar">

        </div>

        <!-- COLONNE DU MILIEU : Trajets populaires -->
        <div class="footer-column">
            <h3>TRAJETS POPULAIRES</h3>
                <ul>
                    <li><a href="<?= Url::to(['site/reserver', 'depart' => 'Avignon', 'arrivee' => 'Marseille']) ?>">Avignon → Marseille</a></li>
                    <li><a href="<?= Url::to(['site/reserver', 'depart' => 'Avignon', 'arrivee' => 'Lyon']) ?>">Avignon → Lyon</a></li>
                    <li><a href="<?= Url::to(['site/reserver', 'depart' => 'Lyon', 'arrivee' => 'Paris']) ?>">Lyon → Paris</a></li>
                    <li><a href="<?= Url::to(['site/reserver', 'depart' => 'Marseille', 'arrivee' => 'Nice']) ?>">Marseille → Nice</a></li>
                    <li><a href="<?= Url::to(['site/reserver', 'depart' => 'Paris', 'arrivee' => 'Lyon']) ?>">Paris → Lyon</a></li>
                </ul>
        </div>

        <!-- COLONNE DROITE : À propos -->
        <div class="footer-column">
            <h3>À PROPOS</h3>
            <ul>
                <li><a href="#">À propos de CERIcar</a></li>
                <li><a href="#">Comment ça marche</a></li>
                <li><a href="#">Centre d’aide</a></li>
                <li><a href="#">Mentions légales</a></li>
            </ul>
        </div>

    </div>

    <!-- COPYRIGHT -->
    <div class="footer-bottom">
        ©2025, CERIcar — Projet universitaire
    </div>

</footer>