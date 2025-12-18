<?php
use yii\helpers\Url;
?>

<!-- ============================= -->
<!-- SECTION 1 : HERO / INTRO -->
<!-- ============================= -->
<section class="hero">
    <div class="hero-text">
        <h1>Économisez du temps, de l’argent… et voyagez ensemble en toute simplicité.</h1>
        <p>
            CERICar connecte des voyageurs qui partagent les mêmes destinations.
            Vous profitez d’un trajet agréable, sécurisé et à moindre coût,
            tout en rencontrant de nouvelles personnes.
        </p>
    </div>

    <div class="hero-image">
        <img src="<?= Yii::getAlias('@web/img/hero_car.png') ?>" alt="CERICar illustration">
    </div>
</section>


<!-- ============================= -->
<!-- SECTION 2 : FORMULAIRE DE RECHERCHE -->
<!-- ============================= -->
<section class="search-box">

    <form class="search-grid">

        <!-- Ville de départ -->
        <div class="input">
            <i class="fa-solid fa-location-dot"></i>
            <input type="text" placeholder="Ville de départ">
        </div>

        <!-- Ville d’arrivée -->
        <div class="input">
            <i class="fa-solid fa-flag"></i>
            <input type="text" placeholder="Ville d’arrivée">
        </div>

        <!-- Date (demain par défaut + min=demain) -->
        <div class="input">
            <i class="fa-solid fa-calendar"></i>
            <input type="text" id="dateInput" readonly>
        </div>
 
        <!-- Nombre de voyageurs -->
        <div class="input">
            <i class="fa-solid fa-user-group"></i>
            <input type="number" min="1" placeholder="Nombre de voyageurs">
        </div>

        <!-- Bouton rechercher -->
        <button type="submit" class="btn-search">
            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
        </button>

    </form>

    <!-- Checkbox en dessous et alignée à gauche 
    <div class="checkbox-wrapper">
        <label class="checkbox">
            <input type="checkbox"> Accepter les correspondances
        </label>
    </div>-->

</section>



<!-- ============================= -->
<!-- SECTION 3 : COMMENT ÇA MARCHE -->
<!-- ============================= -->
<section class="how-section-image">
    <h2 class="how-title">Comment ça marche ?</h2>
    <p class="how-subtitle">
        Trouvez votre covoiturage au meilleur prix.<br>
        Rapide, simple et fiable.
    </p>

    <div class="how-image-container">
        <img src="<?= Yii::getAlias('@web/img/comment.png') ?>" 
             alt="Comment ça marche CERICar">
    </div>
  </section> 

<!-- ============================= -->
<!-- SECTION 4 : AVANTAGES -->
<!-- ============================= -->
<section class="avantages">

  <div class="avantages-header">
    <h2>Les avantages de <span>CERICar</span></h2>
    <p>Covoiturez en toute simplicité : rapide, économique et adapté à vos trajets.</p>
  </div>

  <div class="avantages-list">

    <!-- 01 -->
    <div class="avantage-card">
      <div class="avantage-text">
        <span class="num">01.</span>
        <h3>Recherche instantanée</h3>
        <p>Entrez votre trajet et trouvez des voyages disponibles en quelques secondes.</p>
      </div>
      <div class="avantage-image">
        <img src="<?= Yii::getAlias('@web/img/av1.png') ?>" alt="">
      </div>
    </div>

    <!-- 02 -->
    <div class="avantage-card reverse">
      <div class="avantage-image">
        <img src="<?= Yii::getAlias('@web/img/av2.png') ?>" alt="">
      </div>
      <div class="avantage-text">
        <span class="num">02.</span>
        <h3>Itinéraires optimisés</h3>
        <p>CERICar choisit automatiquement le meilleur trajet, direct ou avec correspondances.</p>
      </div>
    </div>

    <!-- 03 -->
    <div class="avantage-card">
      <div class="avantage-text">
        <span class="num">03.</span>
        <h3>Confort & sécurité</h3>
        <p>Conducteur, véhicule, prix, places… tout est affiché simplement pour vous aider à choisir.</p>
      </div>
      <div class="avantage-image">
        <img src="<?= Yii::getAlias('@web/img/av3.png') ?>" alt="">
      </div>
    </div>

    <!-- 04 -->
    <div class="avantage-card reverse">
      <div class="avantage-image">
        <img src="<?= Yii::getAlias('@web/img/av4.png') ?>" alt="">
      </div>
      <div class="avantage-text">
        <span class="num">04.</span>
        <h3>Prix réduits</h3>
        <p>Partagez les frais et voyagez à moindre coût.</p>
      </div>
    </div>

    <!-- 05 -->
    <div class="avantage-card">
      <div class="avantage-text">
        <span class="num">05.</span>
        <h3>Réservation facile</h3>
        <p>Connectez-vous et réservez vos places en un clic.</p>
      </div>
      <div class="avantage-image">
        <img src="<?= Yii::getAlias('@web/img/av5.png') ?>" alt="">
      </div>
    </div>

  </div>
</section>


<!-- ============================= -->
<!-- SECTION 5: PROPOSER UN VOYAGE -->
<!-- ============================= -->
<section class="proposer-voyage">

  <div class="proposer-container">

    <div class="proposer-text">
      <h2>Un trajet ? Partagez-le !</h2>
      <p>Proposez votre trajet et partagez les frais.</p>
      <a href="<?= Yii::$app->urlManager->createUrl(['voyage/create']) ?>" class="btn-proposer">
        Proposer un voyage
      </a>
    </div>

    <div class="proposer-image">
      <img src="<?= Yii::getAlias('@web/img/proposer.png') ?>" alt="Proposer un trajet">
    </div>

  </div>

</section>

<!-- ============================= -->
<!-- SECTION 6 : TRAJETS POPULAIRES -->
<!-- ============================= -->
<section class="popular">
    <h2>Où souhaitez-vous aller ?</h2>
    <p>Découvrez les trajets populaires et trouvez votre covoiturage en quelques secondes.</p>

    <div class="popular-grid">

        <a href="<?= Url::to(['site/reservation', 'from' => 'Avignon', 'to' => 'Marseille']) ?>" class="popular-card">
            <img src="<?= Yii::getAlias('@web/img/trajet1.jpg') ?>" alt="">
            <h3>Avignon → Marseille</h3>
            <p>Dès 9,50 € — Départ : 08:30</p>
        </a>

        <a href="<?= Url::to(['site/reservation', 'from' => 'Avignon', 'to' => 'Lyon']) ?>" class="popular-card">
            <img src="<?= Yii::getAlias('@web/img/trajet2.jpg') ?>" alt="">
            <h3>Avignon → Lyon</h3>
            <p>Dès 12,00 € — Départ : 10:30</p>
        </a>

        <a href="<?= Url::to(['site/reservation', 'from' => 'Paris', 'to' => 'Lyon']) ?>" class="popular-card">
            <img src="<?= Yii::getAlias('@web/img/trajet3.jpg') ?>" alt="">
            <h3>Paris → Lyon</h3>
            <p>Dès 18,00 € — Départ : 09:30</p>
        </a>

        <a href="<?= Url::to(['site/reservation', 'from' => 'Marseille', 'to' => 'Nice']) ?>" class="popular-card">
            <img src="<?= Yii::getAlias('@web/img/trajet4.jpg') ?>" alt="">
            <h3>Marseille → Nice</h3>
            <p>Dès 7,90 € — Départ : 10:00</p>
        </a>

    </div>
</section>