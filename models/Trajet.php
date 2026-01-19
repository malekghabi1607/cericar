<?php

namespace app\models;

use yii\db\ActiveRecord;

class Trajet extends ActiveRecord
{
    /**
     * Nom réel de la table en base (avec schéma)
     */
    public static function tableName()
    {
        return 'fredouil.trajet';
    }

    /**
     * Règles de validation des champs
     */
    public function rules()
    {
        return [
            [['depart', 'arrivee', 'distance'], 'required'],      // champs obligatoires
            [['distance'], 'integer'],                             // distance doit être un entier
            [['depart', 'arrivee'], 'string', 'max' => 25],       // villes = chaînes max 25 caractères
        ];
    }
    
    /**
     * Récupère un trajet à partir de deux villes
     * Utilisé lors de la recherche utilisateur
     */
    public static function getTrajet($depart, $arrivee)
    {
        return self::findOne([
            'depart'  => $depart,
            'arrivee' => $arrivee
        ]);
    }

    /**
     * Recherche des trajets avec correspondances (recursif, max 2 correspondances).
     *
     * @return array<int, array<int, self>> Liste de chemins (chaque chemin = liste de trajets).
     */
    public static function findPaths(string $depart, string $arrivee, int $maxStops = 2): array
    {
        $trajets = self::find()->all();
        $byDepart = [];
        foreach ($trajets as $trajet) {
            $byDepart[$trajet->depart][] = $trajet;
        }

        $maxSegments = $maxStops + 1;
        $paths = [];
        $visited = [$depart => true];

        $dfs = function (string $current, array $segments) use (
            &$dfs,
            &$paths,
            &$visited,
            $byDepart,
            $arrivee,
            $maxSegments
        ) {
            if (count($segments) >= $maxSegments) {
                return;
            }

            foreach ($byDepart[$current] ?? [] as $trajet) {
                $nextCity = $trajet->arrivee;
                if (isset($visited[$nextCity])) {
                    continue;
                }

                $newSegments = array_merge($segments, [$trajet]);
                if ($nextCity === $arrivee) {
                    $paths[] = $newSegments;
                    continue;
                }

                if (count($newSegments) < $maxSegments) {
                    $visited[$nextCity] = true;
                    $dfs($nextCity, $newSegments);
                    unset($visited[$nextCity]);
                }
            }
        };

        $dfs($depart, []);

        return $paths;
    }
}
