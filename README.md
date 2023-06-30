# PRAM

Le git original de l'application est en accessible en mode privé. Ce dépôt est une copie et n'a pas été développé par mes soins. 

Application cartographique du PRAM Grand Est 

- [License](#license)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Base de données](#bdd)
- [Configuration](#configuration)
- [Rôle](#role)
- [Utilisation](#utilisation)

## License
Ce code est publié sous licence GPL; voir le fichier LICENCE pour plus de détails.

## Prérequis
    * PHP >= 7
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Mbstring PHP Extension
    * PostGis
    * Composer
    * Git
    
## Installation

1) Se placer dans votre repertoire racine Wamp/Xampp (ex pour wamp : wamp64/www), ouvrir une console et lancer un git clone :
```sh
git clone https://github.com/CEN-Lorraine/pram.git
```
2) Se rendre à la racine de votre application, ouvrir une console et lancer la commande suivante :
```sh
composer update
```
Cette commande va créer un dossier vendor et récupérer tous les "packages" nécessaires au bon fonctionnement de l'application.

3) Une fois le clone effectué, renommer le fichier .env.example en .env à la racine de l'application en indiquant la connexion à votre base de données dans les champs suivants :
```sh
DB_CONNECTION=pram
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=le_nom_de_votre_bdd ==> A modifier
DB_USERNAME=utilisateur_de_votre_bdd ==> A modifier
DB_PASSWORD=mdp_utilisateur ==> A modifier
DB_SCHEMA="pram"
```

L'application PRAM est reliée à un autre schéma "tickets" (non obligatoire) pour la saisie de tickets/bugs par les utilisateurs
Dans le .env rajouter :
```sh
DB_CONNECTION_TICKETS=tickets
DB_HOST_TICKETS=127.0.0.1
DB_PORT_TICKETS=5432
DB_DATABASE_TICKETS=le_nom_de_votre_bdd ==> A modifier
DB_USERNAME_TICKETS=utilisateur_de_votre_bdd ==> A modifier
DB_PASSWORD_TICKETS=mdp_utilisateur ==> A modifier
DB_SCHEMA_TICKETS="tickets"
```
3) Ouvrir une console à la racine de votre application, et lancer les commandes suivantes :

```sh
php artisan key:generate
php artisan optimize:clear
php artisan config:cache
```
La première commande va générer une clé pour votre application.
La deuxième va vider le cache de l'application/
la troisième va enregistrer la configuration de l'application en cache.

## Base de données
Avant de lancer la commande de migration des tables, il va falloir créer directement dans votre schéma les tables suivantes :
- communes
- departements
- intercommunalites
- epcitab_ge

Détail des colonnes nécessaire pour la table communes

| Colonnes       | Types    | Obligatoire   | informations complémentaires                          |
| ------------- |:---------:| :------------:| -----------------------------------------------------:|
| id            | integer   | OUI           | L'id doit correspondre au numéro insee de la commune  |
| geom          | geometry  | OUI           |                                                       |
| insee         |varchar(80)| OUI           |                                                       |
| nom           |varchar(80)| OUI           |                                                       |

Détail des colonnes nécessaire pour la table departements

| Colonnes       | Types    | Obligatoire   | informations complémentaires                          |
| ------------- |:---------:| :------------:| -----------------------------------------------------:|
| id            | integer   | OUI           | L'id doit correspondre au numéro code_insee du département  |
| geom          | geometry  | OUI           |                                                       |
| code_insee    |varchar(80)| OUI           |                                                       |
| nom           |varchar(80)| OUI           |                                                       |

Détail des colonnes nécessaire pour la table intercommunalites

| Colonnes       | Types    | Obligatoire   | informations complémentaires                          |
| ------------- |:---------:| :------------:| -----------------------------------------------------:|
| id            | integer   | OUI           | L'id doit correspondre au numéro siren de l'intercommunalité  |
| geom          | geometry  | OUI           |                                                       |
| siren         |numeric    | OUI           |                                                       |
| raison_soc    |varchar(150)| OUI          |                                                       |
| code_dept     |varchar(5) | OUI           | Correspond au code departement de l'intercommunalité  |

Détail des colonnes nécessaire pour la table epcitab_ge (table de relation entre les intercommunalités et les départements et entre les intercommunalites et les communes)

| Colonnes       | Types    | Obligatoire   | informations complémentaires                          |
| ------------- |:---------:| :------------:| -----------------------------------------------------:|
| id            | integer   | OUI           |                                                       |
| dep_epci      | varcher(5)| OUI           |numéro du departement                                  |
| siren         | numeric   | OUI           |numéro de l'intercommunalité                           |
| insee         | varchar(5)| OUI           |numéro de la commune                                   |

Une fois les tables ci-dessus créées, lancer la commande suivante pour générer toutes les autres tables :
```sh
php artisan:migrate
```
Pour tester la connexion, vous pouvez utiliser l'utilisateur "test" qui a été créé au moment de la migration des tables :
email : user@cen.fr
MDP : test1234

## Configuration
1) structure du dossier public :
    Pour le bon fonctionnement de l'application, il va falloir créer quelques dossiers à l'intérieur du dossier public de votre applicatio pram. Voici la structure et les dossiers à créer :
    
    - configuration (a créer)
        - image_page_accueil (a créer)
        - logo (a créer)
        - logo_partenaires (a créer)
        - region_geojson (a créer)
    - exports (a créer)
    - upload_photo_fiche (a créer)

    Reste de la structure déjà en place :
    
    - css
    - fichiers
    - images
    - import
    - js
    - json
    - stade_mare_images
    - vegetation
    - webfonts

2) Configuration générale :
- Connectez-vous avec l'utilisateur test, qui a un rôle développeur (description des rôles plus bas) :
- Se rendre dans : Paramètres => Autres => Configuration générale
- Sur cette page vous pouvez configurer :

| Description                                           | Champs par défauts (si non remplis)   |
| ------------------------------------------------------|:-------------------------------------:|
| Le texte sur l'image de la page d'accueil             | texte pram Grand Est                  |
| le titre de la page d'accueil                         | titre pram Grand Est                  |
| le texte de la page d'accuei                          | texte pram Grand Est                  |
| image de la page d'accueil                            | image pram Grand Est                  |
| logo de votre application                             | Logo pram Grand Est                   |
| L'url de redirection lors du clique sur le logo de la page d'accueil | https://www.pram-grandest.fr/   |
| ----------------------------------------------------- | ------------------------------------  |
| Latitude du centroide par défaut (consultation carte) | centroide Grand Est                   |
| Longitude du centroide par défaut (consultation carte) | centroide Grand Est                  |
| upload fichier GEOJSON du contour de votre région     | geojson Grand Est                     |

=> **IMPORTANT: Après chaque modification de la configuration générale, cliquer sur votre nom en haut à droite, et cliquer sur VIDER LE CACHE !**

## Role
Voici la description des rôle de l'application PRAM :
- Développeur : Accès a toutes les fonctionnalités de l'application + connexion sur autre profil + logs + création utilisateurs + validation demandes comptes
- Administrateur : Accès à toutes les fonctionnalités de l'application + création utilisateurs + validation demandes comptes
- Gestionnaire : saisie mares + validation mares (sur les departements sélectionnés) + paramètres taxons et faunes/flores/EEE
- Utilisateur : saisie mares + consultation mares

## Utilisation

A venir
