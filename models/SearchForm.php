<?php
namespace app\models;

use yii\base\Model;

class SearchForm extends Model
{
    public $depart;
    public $arrivee;
    public $nbVoyageurs;
    public $acceptCorrespondance;

    public function rules()
    {
        return [
            [['depart', 'arrivee', 'nbVoyageurs'], 'required'],
            [['nbVoyageurs'], 'integer', 'min' => 1],
            [['acceptCorrespondance'], 'boolean'],
        ];
    }

}
