# Application de Gestion de Places de Parking

Application web développée avec Laravel et Breeze pour gérer les réservations de places de parking pour le personnel.

## Fonctionnalités

### Pour les utilisateurs standard
- Authentification sécurisée
- Visualisation des places de parking disponibles
- Demande de réservation automatique (attribution aléatoire)
- Suivi des réservations actives
- Gestion de la liste d'attente automatique
- Historique des réservations

### Pour les administrateurs
- Tableau de bord administrateur avec statistiques
- Gestion des places de parking (ajout, modification, suppression)
- Gestion des utilisateurs (ajout, modification, suppression)
- Gestion de la liste d'attente (visualisation, modification de l'ordre)
- Surveillance des réservations actives

## Prérequis techniques
- PHP 8.1 ou supérieur
- MySQL 5.7 ou supérieur
- Composer

## Installation

1. Cloner le dépôt
```bash
git clone https://github.com/votre-utilisateur/parking-management.git
cd parking-management
```

2. Installer les dépendances
```bash
composer install
npm install
npm run build
```

3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurer la base de données dans le fichier `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_parking
DB_USERNAME=root
DB_PASSWORD=
```

5. Exécuter les migrations et les seeders
```bash
php artisan migrate
php artisan db:seed
```

6. Lancer l'application
```bash
php artisan serve
```

## Identifiants par défaut

### Administrateur
- Email: admin@example.com
- Mot de passe: password

### Utilisateurs standard
- Email: jean@example.com, marie@example.com, pierre@example.com, etc.
- Mot de passe: password

## Structure de l'application

- **Modèles** : User, ParkingSpace, ParkingReservation, ParkingWaitingList
- **Contrôleurs** : AdminController, ParkingSpaceController, ParkingReservationController, ParkingWaitingListController
- **Vues** : Organisation en sections - dashboard, parking, admin

## Déploiement en production

Pour déployer cette application en production :

1. Configurer un serveur Web (Apache, Nginx)
2. Sécuriser le fichier .env avec des identifiants de production
3. Configurer HTTPS pour les connexions sécurisées
4. Mettre à jour les permissions des répertoires :
```bash
chmod -R 755 .
chmod -R 777 storage bootstrap/cache
```
5. Exécuter les commandes d'optimisation :
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Contacts

Pour toute question ou assistance, veuillez contacter l'équipe de développement.
