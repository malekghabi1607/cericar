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

![Landing Page](docs/maquette/Landing%20Page.jpg)
![Search Page](docs/maquette/search%20page.jpg)
![Propose Trip Page](docs/maquette/Propose%20Trip%20Page.jpg)
![Login Page](docs/maquette/Login%20Page.jpg)
![Register Page](docs/maquette/Register%20Page.jpg)
![My Trips Page](docs/maquette/My%20Trips%20Page.jpg)

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
