# OrderApp
Gestion des commandes

## Requirements
- **PHP**: version >= 8.2
- **Composer**: version >= 2.8
- Serveur web (Symfony recommandé)

## Setting Symfony Project
1. Cloner le dépôt
   - `git clone https://github.com/votre-org/orderapp.git`
2. Installer les dépendances
   - `cd orderapp/`
   - `composer install`
3. Lancer le serveur de développement
   - `php -S localhost:8000 -t public`

## Running the application
- Accéder à: `http://localhost:8000`
- Vérifier les routes via le profiler Symfony

## Base de données
- Base de données utilisée : MySQL
- Commandes Doctrine rapides:
  - Créer la base: `php bin/console doctrine:database:create`
  - Créer les tables: `php bin/console doctrine:migrations:migrate`
  - Générer des fixtures: `php bin/console doctrine:fixtures:load`

## Utilisation
- Page d’accueil: liste des commandes
- Détail d’une commande: clic sur une commande pour afficher les détails
- Mise à jour du statut: bouton dans la fiche commande

## Bonnes pratiques
- Utiliser FormType Symfony pour les formulaires (sécurité, validation)
- Ajouter des tests unitaires et fonctionnels
- Mettre en place des messages flash et session pour l’utilisateur

## Licence
- Open Source
