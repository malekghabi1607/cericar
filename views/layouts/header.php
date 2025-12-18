<?php
use yii\helpers\Url;
?>

<!-- OVERLAY -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<!-- MENU LATERAL -->
<div id="sideMenu" class="side-menu">
    <a href="<?= Url::to(['search/index']) ?>">
        <i class="fa-solid fa-car-side"></i> Rechercher un trajet
    </a>
    <a href="<?= Url::to(['site/proposer']) ?>">
        <i class="fa-solid fa-circle-plus"></i> Proposer un voyage
    </a>
</div>

<header class="header">
    <div class="header-container">

        <!-- LOGO -->
        <div class="logo">
            <img src="<?= Yii::getAlias('@web/img/logo.png') ?>" alt="Logo CERICar">
        </div>

        <!-- NAV DESKTOP -->
        <nav class="nav">
           <a href="<?= Url::to(['voyage/recherche']) ?>">
               <i class="fa-solid fa-car-side"></i> Rechercher un trajet
           </a>
            <a href="<?= Url::to(['site/proposer']) ?>">
                <i class="fa-solid fa-circle-plus"></i> Proposer
            </a>
        </nav>

        <!-- ICONES A DROITE -->
        <div style="display:flex; align-items:center; gap:15px;">
            
            <!-- BURGER MENU -->
            <div class="burger" onclick="openMenu()">
                <i class="fa-solid fa-bars"></i>
            </div>

            <!-- USER ICON -->
            <div class="user-icon">
                <a href="<?= Url::to(['site/login']) ?>">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>

        </div>

    </div>
</header>
