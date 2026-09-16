# Portfolio Backend (API)

Bienvenue sur le dépôt back-end de mon portfolio personnel. Ce projet expose l'API nécessaire pour alimenter mon interface front-end (Nuxt). Il gère les données telles que mes projets, mes compétences, et la sécurisation des accès.

## Technologies Utilisées

Ce projet est une API robuste développée avec l'écosystème PHP/Symfony :

- **[PHP](https://www.php.net/)** (>= 8.2)
- **[Symfony](https://symfony.com/)** (v7.4) : Le framework PHP pour créer des applications web.
- **[API Platform](https://api-platform.com/)** (v4.3) : Framework pour construire des API web REST pilotées par les données.
- **[Doctrine ORM](https://www.doctrine-project.org/)** : Pour la gestion de la base de données.
- **[LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle)** : Pour la sécurisation de l'API via des tokens JWT (JSON Web Tokens).
- **[VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle)** : Pour la gestion de l'upload des fichiers (images des projets, CV, etc.).

## Prérequis

- PHP >= 8.2
- Composer
- Un serveur web (Apache, Nginx) ou Symfony CLI
- Une base de données relationnelle (MySQL, PostgreSQL, MariaDB...)

## Installation

1. **Cloner le projet et installer les dépendances :**

```bash
composer install
```

2. **Configuration de l'environnement :**

Copiez le fichier `.env` (si non existant, basez-vous sur les variables par défaut) en `.env.local` et configurez vos variables d'environnement, en particulier la connexion à la base de données (`DATABASE_URL`).

3. **Générer les clés SSL pour JWT (si applicable pour l'environnement local) :**

```bash
php bin/console lexik:jwt:generate-keypair
```

4. **Créer la base de données et appliquer les migrations :**

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Démarrage

Utilisez le serveur local de Symfony pour le développement :

```bash
symfony server:start
```

L'API et la documentation seront accessibles sur l'URL fournie par Symfony CLI (souvent `http://127.0.0.1:8000`).

## Documentation de l'API

L'API est auto-documentée grâce à API Platform. Une fois le serveur lancé, vous pouvez accéder à l'interface Swagger UI (généralement via le point d'entrée `/api`) pour explorer, comprendre et tester les différents endpoints disponibles pour le frontend.
