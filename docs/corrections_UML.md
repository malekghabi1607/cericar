# Résumé des corrections UML – Projet CERICar

## 1. Corrections sur le Diagramme de Classes
### ✔ Classes cohérentes avec la base PostgreSQL
- **Internaute**, **Trajet**, **Voyage**, **Reservation**, **Typevehicule**, **Marquevehicule** → conservées.
- Attributs conformes à la BD fredouil.

### ✔ Attributs ajoutés (non persistants)
Pour améliorer la logique métier sans modifier la BD :
- Internaute → *telephone*, *adresse*, *dateInscription*
- Trajet → *dureeEstimee*
- Voyage → *dateDepart*, *heureArrivee*, *dateArrivee*
- Reservation → *dateReservation*, *montant*

### ✘ Classes supprimées
- **Vehicule** → supprimée (n’existe pas dans la BD)

### ✘ Attributs retirés
- “statut” dans Voyage → retiré
- Attributs de paiement → supprimés

### ✔ Relations maintenues
- Internaute → Voyage (conducteur)
- Internaute → Reservation (voyageur)
- Voyage → Trajet, Typevehicule, Marquevehicule, Reservation

## 2. Corrections sur les Cas d’Utilisation
- Tous les UC principaux sont conservés.
- Suppression paiement.
- UC réservation reçue reste valide.

## 3. Corrections sur les Diagrammes de Séquence
- Gardés.
- Suppression paiement.

## 4. Diagramme d’États
- Valide conceptuellement.
- Non implémenté car pas de champ “statut”.

## 5. Cohérence générale
100 % cohérent entre UML, BD et modèles Yii2.
"""