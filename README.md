# Application d'Échecs

## Description
Ce projet est une application d'échecs où les utilisateurs peuvent s'inscrire, se connecter, et voter pour le prochain coup dans une partie d'échecs. Ce README décrit la structure du projet.

## Structure du Projet

### Dossiers

- **`app/`** - Contient la logique principale de l'application divisée en plusieurs sous-dossiers pour les contrôleurs, les modèles et les vues.
  - **`Controllers/`** - Contient les fichiers de contrôleurs qui gèrent les requêtes des utilisateurs et la logique d'interaction.
    - `GameController.php` - Gère la logique spécifique aux actions du jeu d'échecs.
    - `UserController.php` - Gère la logique d'authentification et de gestion des utilisateurs.
  - **`Models/`** - Contient les modèles qui interagissent avec la base de données.
    - `User.php` - Représente l'entité utilisateur et gère l'authentification, l'enregistrement, etc.
    - `Game.php` - Représente une partie d'échecs, stockant l'état actuel et l'historique des coups.
    - `Vote.php` - Gère les votes des utilisateurs pour les prochains coups.
  - **`Views/`** - Contient les templates pour l'interface utilisateur, organisés par sections.
    - **`layouts/`** - Contient les templates de base.
    - **`auth/`** - Contient les vues pour les fonctionnalités d'authentification.
    - **`game/`** - Contient les vues relatives au jeu d'échecs.

- **`config/`** - Contient les fichiers de configuration de l'application.
  - `app.php` - Configuration générale de l'application.
  - `database.php` - Configuration de la base de données.

- **`database/`** - Contient les migrations et seeders pour la base de données.
  - **`migrations/`** - Scripts pour créer la structure de la base de données.
  - **`seeds/`** - Scripts pour peupler la base de données avec des données initiales.

- **`public/`** - Dossier racine accessible au public. Contient les fichiers statiques et le point d'entrée principal.
  - **`css/`** - Contient les feuilles de style CSS.
  - **`js/`** - Contient les scripts JavaScript.
  - `index.php` - Fichier principal qui initie l'application.
  - `.htaccess` - Configuration pour le serveur web Apache, notamment pour la réécriture d'URL.

- **`routes/`** - Définit les routes HTTP de l'application.
  - `web.php` - Routes pour l'interface web.
  - `api.php` - Routes pour l'API (si nécessaire).

- **`resources/`** - Contient les ressources frontales qui ne sont pas directement servies au public.
  - **`sass/`** - Contient les fichiers SASS pour les styles.
  - **`js/`** - Scripts JS frontaux.

- **`storage/`** - Utilisé pour le stockage local de l'application, incluant les logs et caches.
  - **`logs/`** - Logs de l'application.
  - **`framework/cache/`** - Cache utilisé par le framework.

- **`tests/`** - Contient les tests automatisés de l'application.
  - **`Feature/`** - Tests de fonctionnalités.
  - **`Unit/`** - Tests unitaires.

- **`vendor/`** - Contient les bibliothèques de tiers gérées par Composer.

### Fichiers

- **`.env`** - Fichier pour la configuration des variables d'environnement spécifiques à l'environnement.

## Installation et configuration

Expliquer comment configurer et démarrer l'application, y compris la configuration de la base de données, les dépendances, etc.

## Utilisation

Détails sur comment utiliser l'application, y compris des exemples de requêtes et de réponses si nécessaire.

## Licence

Informations sur la licence sous laquelle le projet est distribué.

## Contribution

Directives pour contribuer au projet.
