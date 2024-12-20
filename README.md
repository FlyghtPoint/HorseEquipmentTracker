# Horse Equipment Tracker

Une application web qui permet de consulter et de réserver du matériel en rapport avec le monde de l'équitation.

## Overview

Horse Equipment Tracker is a Symfony-based application that helps equestrians manage their equipment inventory efficiently. The application features a PHP backend with GraphQL integration for flexible data querying.
L'application est basée sur Symfony (7.1.9). Un back-office constitué d'un tableau de bord pour les administrateurs est disponible. Les données sont stockées en base de donnée et accessible depuis une api.

## Prerequisites

- PHP 8.3
- Composer
- Make

## Installation

1. Clone the repository:

```bash
git clone https://github.com/FlyghtPoint/HorseEquipmentTracker
cd HorseEquipmentTracker
```

2. Install dependencies:

```bash
make install
```

3. Configure environment:

```bash
cp .env .env.local
```

Edit .env.local with your database credentials and other configuration settings.

## Development

Start the development server:

```bash
make dev
```

Run tests:

```bash
make test
```

## Build

Build for production:

```bash
make build
```

## Database

Initialize the database:

```bash
make db-init
```

Run migrations:

```bash
make db-migrate
```
