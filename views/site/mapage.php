<?php
use yii\helpers\Html;
$this->registerCssFile('@web/css/site.css?v=1');
/* @var $message string */
/* @var $produits app\models\Produits */

$this->title = 'Ma Page';
$map = $produits->asMap();

?>

<div class="site-mapage">
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="message"><?= Html::encode($message) ?></p>

    <div class="form-section">
        <h3>Choisissez un produit :</h3>
        <?= Html::dropDownList(
            'produit',
            null,
            $map,
            ['prompt' => '--- Sélectionnez ---', 'class' => 'select-produit']
        ) ?>
    </div>
</div>
