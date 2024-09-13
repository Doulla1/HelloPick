# HelloPick

HelloPick est une application web développée avec Laravel 11, Blade, et Livewire. Elle permet aux employés de répondre à des sondages quotidiens amusants et de consulter les résultats en temps réel. Conçue pour améliorer l'engagement et la cohésion d'équipe au sein de l'entreprise, HelloPick offre une expérience utilisateur simple et intuitive.

## Fonctionnalités

- **Authentification des administrateurs** : Seuls les administrateurs peuvent s'autentifier et accéder à l'interface d'administration.
- **Creation de comptes administrateurs** : Les administrateurs peuvent créer de nouveaux comptes administrateurs.
- **Gestion des profils** : Les administrateurs peuvent modifier ou supprimer leur profil et également gérer leur mot de passe.
- **Visualisation des utilisateurs** : Les utilisateurs peuvent consulter la liste des profils des administrateurs.
- **Création de sondages** : Les administrateurs peuvent créer un nouveau sondage chaque jour avec plusieurs options de réponse.
- **Participation aux sondages** : Les employés peuvent répondre au sondage du jour anonymement.
- **Affichage des résultats** : Les résultats des sondages sont affichés de manière anonyme après la participation.
- **Consultation des anciens sondages** : Les utilisateurs peuvent consulter les sondages précédents et leurs résultats globaux.

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
5. Configurez votre base de données dans le fichier `.env`
6. Générez une clé d'application :
   ```bash
   php artisan key:generate
7. Exécutez les migrations et les seeders :
   ```bash
    php artisan migrate --seed
8. Lancez le serveur de développement :
   ```bash
   php artisan serve
9. Accédez à l'application dans votre navigateur à l'adresse `http://localhost:8000`
