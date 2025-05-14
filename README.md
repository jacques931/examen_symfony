# Projet Examen Symfony

## Description
Ce projet est une application web développée avec le framework Symfony 7.2. Il s'agit d'une plateforme de gestion de parcours pédagogiques permettant aux utilisateurs de suivre des étapes d'apprentissage, d'accéder à des ressources et de soumettre des rendus.

> **Note :** Ce projet a été réalisé dans le cadre d'un projet scolaire pour démontrer mes compétences techniques et pratiques en développement web.

Accessible via https://sitecorp.alwaysdata.net/
> **Note :** Ce site est susceptible d'être supprimé ou remplacé par un autre projet.

## Fonctionnalités

- **Gestion des utilisateurs** : Inscription, connexion, profils utilisateurs
- **Parcours pédagogiques** : Création et suivi de parcours d'apprentissage
- **Étapes** : Chaque parcours est composé d'étapes à compléter
- **Ressources** : Accès à des ressources pédagogiques pour chaque étape
- **Rendus** : Possibilité de soumettre des travaux pour validation
- **Interface d'administration** : Gestion complète via EasyAdmin

## Structure du projet

### Entités principales
- **User** : Gestion des utilisateurs et authentification
- **Parcours** : Définition des parcours pédagogiques
- **Etapes** : Étapes à suivre dans chaque parcours
- **Ressource** : Ressources pédagogiques associées aux étapes
- **Rendus** : Travaux soumis par les utilisateurs
- **Message** : Système de messagerie interne

### Contrôleurs
- **HomeController** : Gestion de la page d'accueil
- **SecurityController** : Gestion de l'authentification
- **RegistrationController** : Inscription des utilisateurs
- **ParcoursController** : Affichage et gestion des parcours
- **RessourceController** : Accès aux ressources pédagogiques
- **RendusController** : Soumission et évaluation des rendus
- **MessageController** : Système de messagerie

### Administration
Le projet utilise EasyAdmin pour l'interface d'administration, accessible via la route `/admin`. Cette interface permet de gérer :
- Les utilisateurs
- Les parcours
- Les étapes
- Les ressources
- Les rendus

## Technologies utilisées

- **Symfony 7.2** : Framework PHP
- **Doctrine ORM** : Gestion de la base de données
- **EasyAdmin 4** : Interface d'administration
- **Twig** : Moteur de templates
- **Bootstrap** : Framework CSS pour l'interface utilisateur

## Installation

1. Cloner le dépôt
```bash
git clone [URL_DU_DEPOT]
```

2. Installer les dépendances
```bash
composer install
```

3. Configurer la base de données dans le fichier `.env`
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/examen_symfony?serverVersion=8.0"
```

4. Créer la base de données
```bash
php bin/console doctrine:database:create
```

5. Exécuter les migrations
```bash
php bin/console doctrine:migrations:migrate
```

6. Charger les fixtures (données de test) si disponibles
```bash
php bin/console doctrine:fixtures:load
```

7. Démarrer le serveur de développement
```bash
symfony server:start
```

## Utilisation

- Accéder à l'application : `http://localhost:8000`
- Accéder à l'administration : `http://localhost:8000/admin`

## Développement

### Commandes utiles

- Créer une entité : `php bin/console make:entity`
- Créer un contrôleur : `php bin/console make:controller`
- Créer une migration : `php bin/console make:migration`
- Vider le cache : `php bin/console cache:clear`

## Licence

Ce projet est développé dans le cadre d'un examen et est soumis à des droits d'auteur.
