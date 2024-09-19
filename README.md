# HelloPick

HelloPick est une application web développée avec Laravel 11, Blade, et Livewire. Elle permet aux employés de répondre à des sondages quotidiens amusants et de consulter les résultats en temps réel. Conçue pour améliorer l'engagement et la cohésion d'équipe au sein de l'entreprise, HelloPick offre une expérience utilisateur simple et intuitive.

# API de Gestion de Profils

Cette API permet de gérer des **profils utilisateurs** et des **administrateurs** via des endpoints sécurisés. Seuls les administrateurs authentifiés peuvent créer, modifier ou supprimer des profils, tandis que les utilisateurs anonymes peuvent consulter certains profils publics (en fonction de leur statut).

## Fonctionnalités

- **Connexion des administrateurs** avec génération de token d'authentification (via Sanctum).
- **Création de nouveaux administrateurs** (par un administrateur existant).
- **Gestion des profils** (création, mise à jour, suppression) avec téléchargement d'image.
- **Consultation des profils publics** (statut "actif") pour les utilisateurs non authentifiés.
- Documentation API générée automatiquement avec **Scribe**.

## Installation

### Prérequis

- **PHP** (>=8.2)
- **Composer**
- **MySQL**, **PostgreSQL**, **SQLite** ou une autre base de données supportée par Laravel

## Technologies utilisées

- **Laravel 11** 
- **Blade**
- **MySQL** 

## Installation

1. Clonez ce dépôt :
   ```bash
   git clone https://github.com/Doulla1/HelloPick
2. Accédez au répertoire du projet :
   ```bash
   cd HelloPick
3. Installez les dépendances :
   ```bash
    composer install
    npm install
    npm run dev
4. Créez une copie du fichier `.env.example` et renommez-la `.env`
5. Configurez votre base de données dans le fichier `.env`. par exemple :
   ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_db_user
    DB_PASSWORD=your_db_password
6. Générez une clé d'application :
   ```bash
   php artisan key:generate
7. Ajouter les identifiants de l'administrateur initial. Dans le fichier .env, spécifiez les identifiants de l'administrateur initial qui sera créé lors du seed :
   ```bash
    ADMIN_EMAIL=admin@example.com
    ADMIN_PASSWORD=superSecretPassword
8. Exécutez les migrations et les seeders :
   ```bash
   php artisan migrate
   php artisan migrate --seed
9. Lancez le serveur de développement :
   ```bash
   php artisan serve
10. Générer la documentation API. La documentation de l'API est générée automatiquement avec Scribe. Après chaque modification des routes ou de la structure API, générez la documentation avec :
    ```bash
    php artisan scribe:generate
11. Accédez à l'application dans votre navigateur à l'adresse `http://localhost:8000`

La documentation de l'API est accessible à l'adresse `http://localhost:8000/docs`.
