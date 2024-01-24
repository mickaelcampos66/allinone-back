# Projet Symfony - AllinOne

## Récupération du repository

```sh
git clone path/project
```

## Se déplacer dans le dossier du projet 

```sh 
cd projet-03-allinone-back/
```

## Installation des dépendances

```sh
composer install
```

**!! Des erreurs vont apparaître !!**

## Création du fichier d'environnement

```sh
touch .env.local
```

### Remplissage des données dans le fichier d'environnement

```sh
sudo nano .env.local
```

#### - Mettre à l'intèrieur du fichier les données suivantes

```php
    APP_ENV=dev
    DATABASE_URL="mysql://User:Password@localhost:3306/allinone?serverVersion=10.3.25-MariaDBB&charset=utf8mb4"
    JWT_PASSPHRASE=allinone
```

sauvegarder `CTRL + X` mettre `Y` et `Entrée`

### Creation de la base de donnée

```sh
bin/console doctrine:database:create
```

### Mise en place des migrations

```sh
bin/console doctrine:migration:migrate
```

### **!! En cas de problèmes ou d'erreurs !!**

#####  - Se rendre dans le dossier migrations/ et supprimer les fichiers Version[date].php

-   Faire une migration avec `bin/console make:migration`
-   Valider la migration avec `bin/console doctrine:migration:migrate`

### Injection des données SQL dans la table

```sh
mysql -uUser -p allinone < public/docs/data.sql
```

**Si il y a des problèmes avec les tables dans la BDD, il faudra supprimer toutes les tables directement dans Adminer et récuperer les données avec le fichier dataTables.sql**

```sh
mysql -uUser -p allinone < public/docs/dataTables.sql
```

### Generation de nouvelles clés pour le token JWT

```sh
php bin/console lexik:jwt:generate-keypair --overwrite
```

### Lancement du serveur PHP

```sh
php -Slocalhost:8000 -tpublic
```
