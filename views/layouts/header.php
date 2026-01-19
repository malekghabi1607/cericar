<?php
use yii\helpers\Url;
use yii\helpers\Html;

// On récupère les données de session une seule fois
$sessionInternaute = Yii::$app->session->get('internaute');
?>

<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<div id="sideMenu" class="side-menu">
    <a href="<?= Url::to(['voyage/recherche']) ?>">
        <i class="fa-solid fa-car-side"></i> Rechercher un trajet
    </a>
    <a href="<?= Url::to(['voyage/create']) ?>">
        <i class="fa-solid fa-circle-plus"></i> Proposer un voyage
    </a>
</div>

<header class="header">
    <div class="header-container">

       <div class="logo">
            <a href="<?= Url::to(['site/index']) ?>">
                <img src="<?= Yii::getAlias('@web/img/logo.png') ?>" alt="Logo CERICar">
            </a>
        </div>

        <nav class="nav">
           <a href="<?= Url::to(['voyage/recherche']) ?>">
               <i class="fa-solid fa-car-side"></i> Rechercher un trajet
           </a>
            <a href="<?= Url::to(['voyage/create']) ?>">
                <i class="fa-solid fa-circle-plus"></i> Proposer un voyage
            </a>
        </nav>

        <div style="display:flex; align-items:center; gap:15px;">
            
            <div class="burger" onclick="openMenu()">
                <i class="fa-solid fa-bars"></i>
            </div>

            <div class="user-icon">

            <?php if ($sessionInternaute): ?>
                <div class="user-connected">
                    <a href="<?= Url::to(['site/profil']) ?>" class="header-link">
                        <i class="fa-solid fa-circle-user"></i>
                    </a>
                    <a href="<?= Url::to(['site/logout']) ?>" class="header-link logout-link">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            <?php else: ?>
                <a href="<?= Url::to(['site/login']) ?>" class="login-icon">
                    <i class="fa-solid fa-user"></i>
                </a>
            <?php endif; ?>

            </div>
        </div>
    </div>
</header>
