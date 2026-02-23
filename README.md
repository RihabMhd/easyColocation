# 🏠 EasyColoc - Plateforme de Gestion de Colocation

[cite_start]EasyColoc est une application web monolithique basée sur l'architecture **MVC Laravel**[cite: 3]. [cite_start]Elle permet de suivre les dépenses communes, de répartir automatiquement les dettes entre les membres et d'offrir une vision claire de « qui doit quoi à qui » pour éviter les calculs manuels[cite: 1, 2].

## 🚀 Fonctionnalités Clés

### 👥 Gestion des Membres et Colocations
* [cite_start]**Rôles Multiples** : Gestion des accès pour les Membres, les Owners de colocation et un Administrateur Global[cite: 4].
* [cite_start]**Invitations Sécurisées** : Envoi d'invitations via email avec un lien contenant un token unique[cite: 1, 10].
* [cite_start]**Restriction Unique** : Un utilisateur ne peut avoir qu'une seule colocation active à la fois[cite: 11, 16].
* [cite_start]**Système de Réputation** : Score financier (+1/-1) évoluant selon le solde au moment du départ ou de l'annulation d'une colocation[cite: 1, 9].

### 💸 Gestion Budgétaire
* [cite_start]**Suivi des Dépenses** : Ajout de dépenses avec titre, montant, date, catégorie et payeur[cite: 12].
* [cite_start]**Calcul Automatique** : Recalcul instantané des soldes et des parts individuelles lors de chaque nouvel ajout[cite: 1, 13].
* [cite_start]**Simplification des Dettes** : Vue synthétique des remboursements nécessaires[cite: 13].
* [cite_start]**Paiements** : Option « Marquer payé » pour valider les règlements entre membres[cite: 1, 10].

### 🛡️ Administration Plateforme
* [cite_start]**Dashboard Admin** : Accès aux statistiques globales sur les utilisateurs, dépenses et colocations[cite: 4].
* [cite_start]**Modération** : Possibilité de bannir ou débannir des utilisateurs[cite: 5].

## 🛠️ Stack Technique

* [cite_start]**Framework** : Laravel (MVC)[cite: 3].
* [cite_start]**Base de Données** : MySQL / PostgreSQL géré via migrations[cite: 4].
* [cite_start]**ORM** : Eloquent avec relations complexes (hasMany, belongsToMany, Pivot Tables)[cite: 4, 23].
* [cite_start]**Frontend** : Blade, Tailwind CSS et JavaScript natif[cite: 30, 32].
* [cite_start]**Authentification** : Laravel Breeze / Jetstream[cite: 4].

