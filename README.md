# Horse Equipment Tracker

Une application web permettant de consulter et de réserver du matériel équestre.

## Présentation

Horse Equipment Tracker est une application basée sur Symfony (7.1.9) pour gérer efficacement de l'équipement équestre. L'application dispose d'un back-end PHP et d'un front-end utilisant twig ainsi que tailwind UI. Un back-office avec tableau de bord est disponible pour les administrateurs (grâce au bundle easyadmin). Les données sont stockées en base de données et accessibles via une API construite avec API Platform.

## Prérequis

- PHP 8.3
- Composer
- Make

## Installation

1. Cloner le dépôt :

```bash
git clone https://github.com/FlyghtPoint/HorseEquipmentTracker
cd HorseEquipmentTracker
```

2. Installer les dépendances :

```bash
make install
```

3. Configurer l'environnement :

```bash
cp .env .env.local
```

Modifiez le fichier .env.local avec vos identifiants de base de données et autres paramètres de configuration.

## Développement

Démarrer le serveur de développement :

```bash
make dev
```

Lancer les tests :

```bash
make test
```

## Production

Construire pour la production :

```bash
make build
```

## Base de données

Initialiser la base de données :

```bash
make db-init
```

Exécuter les migrations :

```bash
make db-migrate
```
