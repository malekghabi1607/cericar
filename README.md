# CERICar – Projet Web L3

CERICar est une application web de covoiturage développée en Yii2 (template basic) dans le cadre de la Licence 3 Informatique.

![Logo CERICar](web/img/logo.png)

## Objectif
Faciliter la recherche et la réservation de trajets entre utilisateurs, avec une interface claire et des interactions rapides grâce à l'AJAX.

## Fonctionnalités clés
- Recherche de trajets en AJAX (ville de départ, ville d'arrivée, nombre de voyageurs)
- Réservation d'un trajet en AJAX avec mise à jour du nombre de places
- Gestion des utilisateurs non connectés (messages AJAX sans redirection)
- Connexion et inscription classiques avec redirections
- Sécurisation des actions sensibles côté serveur (session, POST)

## Parcours utilisateurs
- Visiteur : consulter les trajets, s'inscrire, se connecter
- Utilisateur connecté : réserver, consulter ses réservations, se déconnecter

## Maquettes et visuels
Extraits de la maquette réalisée pour le projet :

![Landing Page](https://github.com/user-attachments/assets/06b4887b-2d42-4bd0-b702-6c0d55a67a99)

![search page](https://github.com/user-attachments/assets/556cc3b5-539e-4e37-847d-648e1ad6cdb7)
![search page](https://github.com/user-attachments/assets/2d1be2f1-36e4-4a1b-80ac-a81cde80b259)
![Propose Trip Page](https://github.com/user-attachments/assets/47a070aa-64b8-4257-8131-6dde1d958c6c)
![Login Page](https://github.com/user-attachments/assets/604db67d-13b9-4be2-8bfd-f6ef5a4a967d)
![Register Page](https://github.com/user-attachments/assets/00524030-34fb-4ff7-ab47-be467769877f)
![My profile](https://github.com/user-attachments/assets/f8e45d28-5da0-4d71-a4c4-e8b748e8c6ea)

![About Page](https://github.com/user-attachments/assets/5693228b-7779-40c5-9a50-653f96dc8f5f)
![help Page](https://github.com/user-attachments/assets/48b3fe7b-a4db-43fc-a63c-395e79d4b7d7)
![How Page](https://github.com/user-attachments/assets/db66ba11-c28b-459e-8095-5206aad86b96)
![legal Page](https://github.com/user-attachments/assets/f120f624-5ef3-4fd7-8767-9ebdedacbc8c)


## Stack technique
- PHP 7.4+
- Yii2 + Bootstrap 5
- PostgreSQL (base `fredouil` utilisée en cours)

## Installation rapide
1. Installer les dépendances PHP :
   ```bash
   composer install
   ```
2. Configurer la base de données dans `config/db.php`.
3. Lancer l'application :
   ```bash
   php yii serve --port=8080
   ```
4. Ouvrir `http://localhost:8080/`.

## Structure du projet
- `assets/` gestion des assets
- `commands/` commandes console
- `config/` configuration applicative
- `controllers/` contrôleurs web
- `models/` modèles (liaison BDD)
- `views/` vues
- `web/` point d'entrée (`index.php`) et ressources publiques
- `docs/` documentation et maquettes
- `tests/` tests Codeception

## Spécifications
Le fichier `afaire` détaille les parties 5 et 6 du projet (AJAX, sécurité, accès).

## Tests (optionnel)
```bash
vendor/bin/codecept run
```
