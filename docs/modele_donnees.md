# Modèle de données – Projet CERICar

Ce document présente les tables principales du projet CERICar, avec :
- les attributs obligatoires issus de la base existante,
- les attributs supplémentaires utiles proposés pour enrichir le fonctionnement,
- les arguments liés à chaque ajout.

---

## 1. Utilisateur (internaute)

### Attributs obligatoires
- id  
- nom  
- prenom  
- pseudo  
- mail  
- pass  
- photo  
- permis  

### Attributs utiles proposés
- telephone  
- adresse  
- dateInscription  

### Arguments
Ces attributs complètent le profil utilisateur, facilitent la gestion des comptes et améliorent la clarté du système sans modifier la base fonctionnelle.

---

## 2. Trajet (trajet)

### Attributs obligatoires
- id  
- depart  
- arrivee  
- distance  

### Attribut utile proposé
- dureeEstimee (valeur calculée)

### Arguments
Cette valeur permet un affichage cohérent et une meilleure compréhension du trajet sans impact sur le stockage.

---

## 3. Voyage (voyage)

### Attributs obligatoires
- id  
- conducteur  
- trajet  
- idtypev  
- idmarquev  
- tarif  
- nbplacedispo  
- nbbagage  
- heuredepart  
- contraintes  

### Attributs utiles proposés
- dateDepart  
- heureArrivee  
- dateArrivee  

### Arguments
Ces attributs permettent une description complète d’un voyage (date, heure, durée). Ils facilitent le filtrage, la planification et l’estimation.

---

## 4. Réservation (reservation)

### Attributs obligatoires
- id  
- voyage  
- voyageur  
- nbplaceresa  

### Attributs utiles proposés
- dateReservation  
- montant  

### Arguments
Ils permettent de suivre l’historique des réservations et de calculer le coût total, ce qui apporte une meilleure visibilité au système.

---

## 5. TypeVehicule (typevehicule)

### Attributs obligatoires
- id  
- typev  

### Attributs utiles proposés
Aucun.

---

## 6. MarqueVehicule (marquevehicule)

### Attributs obligatoires
- id  
- marquev  

### Attributs utiles proposés
Aucun.

---

## 7. Notification (classe logique, hors base de données)

### Rôle
Classe utilisée dans le modèle conceptuel pour représenter des messages internes ou des alertes du système. Ne nécessite pas de stockage physique.