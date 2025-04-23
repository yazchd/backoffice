# Biocosmetique

## Spécifications techniques

### Images 
| Type | Dimensions |
|------|------------|
| Logo | 119 x 50 px |
| Country flag | 27 x 14 px |

### Palette de couleurs
| Élément | Couleur |
|---------|---------|
| Titres verts | #7fad39 |
| Fond bannière page d'accueil | #f5f5f5 |
| Fond image catégorie | #f5f5f5 |
| Fond image produit unique | #f3f6fB|
| Fond bannière page d'accueil 1 | #f8ebcb|
| Fond bannière page d'accueil 2 | #cfefea|

## Environnement Docker

L'environnement Docker contient :
- PHP 8.2
- Symfony 7.2
- MySQL
- PHPMyAdmin
- MailDev
- MySql vertion 9.1.0   (pour savoir la version serveur sql SELCT VERSION();)

> **Référence :** [Un environnement de développement Symfony 5 avec Docker et Docker Compose](https://yoandev.co/un-environnement-de-d%C3%A9veloppement-symfony-5-avec-docker-et-docker-compose/)

## Commandes principales

### Installation et démarrage
```bash
# Construire les conteneurs
docker-compose build

# Démarrer les conteneurs
docker-compose up -d

# Créer un nouveau projet Symfony
docker exec www_docker_symfony composer create-project symfony/website-skeleton project
```

### Configuration des droits
```bash
# Dans le dossier projet
cd project

# Vérifier le propriétaire (normalement root)
ls -l

# Changer le propriétaire des fichiers
sudo chown -R $USER ./
```

## Configuration de la base de données

### Fichier .env

DATABASE_URL=mysql://root:my_password@127.0.0.1:3306/my_db_name?serverVersion=5.7

# Variables globales
APP_ENV=prod
APP_SECRET=xxxxxxxxxxxxxxxxxx
APP_DOMAIN='http://www.monsite.com'

## parameter le ficher .env.local

 - copier le fichier .env en .env.local 

 - DATABASE_URL=mysql://root:@db_docker_symfony:3306/my_db_name?serverVersion=5.7

 - Parametrer les variables globales ###> symfony/framework-bundle ###

APP_ENV=dev

APP_SECRET=xxxxxxxxxxxxxxxxx

APP_DOMAIN='http://localhost:8963'

et puis se connecter dans le shell du docker 

 - $ docker exec -it www_docker_symfony bash
    ->shel conteneur 
 - /var/www# cd project
 - /var/www/project# php bin/console doctrine:database:create
     ->symfo db create
 =Created database \`db_name\` for connection named default

# Première utilisation de l'application
0- composer update

1- créer la datebase
$ php bin/console doctrine:database:create 

2- Créarion du shcéma de la database
$ php bin/console doctrine:schema:update --force 

3 Créer le user N°1

4- Mettre le user 1 en admin & créate fixtures
$ php bin/console doctrine:migrations:migrate


*5-Pour recommencer
php bin/console doctrine:database:drop --force 


## parameter mail dev dans le fichier .env

MAILER_DSN=smtp://maildev_docker_symfony:25
