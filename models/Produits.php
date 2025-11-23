<?php
namespace app\models;

use yii\helpers\ArrayHelper;

class Produits
{
    /** @var array[] Tableau d’items; chaque item a 'id' et 'produit' */
    public $produits = [];

    public function __construct()
    {
        // même format que l’exemple du cours (figure 1)
        $this->produits = [
            '1' => ['id' => '1', 'produit' => 'Rose'],
            '2' => ['id' => '2', 'produit' => 'Tulipe'],
            '3' => ['id' => '3', 'produit' => 'Jasmin'],
            '4' => ['id' => '4', 'produit' => 'Laurier Rose'],
            '5' => ['id' => '5', 'produit' => 'Orchidée'],
        ];
    }

    /* retourne un tableau id => libellé pour dropDownList */
    public function asMap(): array
    {
        // transforme chaque item ['id'=>..., 'produit'=>...] en paire id => produit
        return ArrayHelper::map($this->produits, 'id', 'produit');
    }
}