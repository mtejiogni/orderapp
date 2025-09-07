# OrderApp
Gestion des commandes

## Requirements
- **PHP**: version >= 8.2
- **Composer**: version >= 2.8
- Serveur web recommandé (WampServer / XampServer)

## Setting Symfony Project
1. Cloner le dépôt
   - `git clone https://github.com/mtejiogni/orderapp.git`
2. Installer les dépendances
   - `cd orderapp/`
   - `composer install`
3. Installer la base de données (MySQL)
   - Configurer le serveur de base de données dans le fichier `.env` 
   - Créer la base de données: `php bin/console doctrine:database:create`
   - Créer les tables: `php bin/console doctrine:migrations:migrate`
4. Lancer le serveur de développement
   - `php -S localhost:8000 -t public`

## Running the application
- Accéder à: `http://localhost:8000`
- Vérifier les routes via le profiler Symfony

## Base de données
- Base de données : MySQL/PostgreSQL/...
- Commandes Doctrine rapides:
  - Créer la base: `php bin/console doctrine:database:create`
  - Créer les tables: `php bin/console doctrine:migrations:migrate`
  - Générer des fixtures: `php bin/console doctrine:fixtures:load`

## Utilisation
- Page d’accueil (tableau de bord): Liste des commandes
- Page de gestion des commandes
- Page de gestion des produits
- Page de gestion des utilisateur

## Bonnes pratiques
- Utiliser FormType Symfony pour les formulaires (sécurité, validation)
- Ajouter des tests unitaires et fonctionnels
- Mettre en place des messages flash et session pour l’utilisateur

## Licence
- Open Source
