# 21/07/2016 GitHub Project creation:

## Drupal 8 Features:
* Should try to embed ASAP the Custom Content Type _Randonnee de Journee_ in the GitHub sources

### Embedding the entire customization
* The target is to be able to recreate the RIF project out of [an empty Drupal8](https://www.drupal.org/8) !!!
* why not then have in the module an install procedure that precisely create the custom content type _Randonnee de Journee_ ...
* Also thinking about an uninstall procedure inpired by [The Delete All Module](https://www.drupal.org/project/delete_all), which can empty Drupal8 from all the nodes of a specific content type !!!

## inserting and deleting without batch !!!
* Just using the database connection

## understanding the [drush batch](https://www.drupal.org/node/873132)
* For operations other than selecting nodes to delete (empty randos)...
* I have to include a condition before inserting !!!
  * Verify that rand Randonnee_Id does not exists ..
  * If that not the case just update not insert ...

## Try to manage to Work with XDebug/Drush:

* I started, but couldn't go further than a certain depth...
* see [my WIKI in french](http://wiki.jpmena.eu/index.php?title=Php:drupal8:drush/xdebug)

# 23/07/2016 GitHub Project creation:
* OREilly's __Learning PHP 7__ book invites at the chapter _Using a Debuggger_ (page 269 in that early release version)
  * to use [phpdbb](http://phpdbg.com/) (Since version 5.6 of PHP)
  * they present it as command line utility

``` bash
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports (master=) $ phpdbg -e test.php
Le programme « phpdbg » n'est pas encore installé. Vous pouvez l'installer en tapant :
sudo apt install php7.0-phpdbg
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports (master *=) $ sudo apt install php7.0-phpdbg
[sudo] Mot de passe de jpmena :
Lecture des listes de paquets... Fait
Construction de l arbre des dépendances       
Lecture des informations d état... Fait
Les NOUVEAUX paquets suivants seront installés :
  php7.0-phpdbg
0 mis à jour, 1 nouvellement installés, 0 à enlever et 21 non mis à jour.
Il est nécessaire de prendre 1 319 ko dans les archives.
Après cette opération, 4 566 ko d espace disque supplémentaires seront utilisés.
Réception de:1 http://fr.archive.ubuntu.com/ubuntu xenial-updates/universe amd64 php7.0-phpdbg amd64 7.0.8-0ubuntu0.16.04.1 [1 319 kB]
1 319 ko réceptionnés en 1s (1 226 ko/s)
Sélection du paquet php7.0-phpdbg précédemment désélectionné.
(Lecture de la base de données... 303913 fichiers et répertoires déjà installés.)
Préparation du dépaquetage de .../php7.0-phpdbg_7.0.8-0ubuntu0.16.04.1_amd64.deb ...
Dépaquetage de php7.0-phpdbg (7.0.8-0ubuntu0.16.04.1) ...
Traitement des actions différées (« triggers ») pour man-db (2.7.5-1) ...
Paramétrage de php7.0-phpdbg (7.0.8-0ubuntu0.16.04.1) ...
update-alternatives: utilisation de « /usr/bin/phpdbg7.0 » pour fournir « /usr/bin/phpdbg » (phpdbg) en mode automatique

Creating config file /etc/php/7.0/phpdbg/php.ini with new version
`̀``

* question does it work in connection with NetBeans ?
* continue working with Batch and controller see [My md page](docs/BATCH.md)
* Les options de phpdbg que j'utilise beaucoup sont :
  * n: passer à la ligne suivante
  * _continue_ : pour passer au prochain arrêt du programme !!!
  * __f 1__ ou __t__ savoir où j'en suis (quelle ligne du programme)...
  * __ev__ pour évaluer une expression pendant que mon programme est arrêté ...
  * _watch_ utilisé dans le livre pour connaître ma variable utilisée ...
  * q ou quit: pour quitter la session !!!

# 26/07/2016 :

* The setting of new value doesnt work ... The object are empty
  * we still have to control that those values are well translated ?
  * à tester avec phpdbg ???

# 31/07/2016

* Toutes les valeurs entrent bien !!!!
* Le Batch est à contruire
  * Bien différencier INSERT et UPDATE !!!

# 18/08/2016

## tester l'ensemble de la châine:

1. Suite à la récupération de la configuration custom via Features,
2. et son ajout au module (stockage du sous module généré),
3. Il me reste à tester une installation complète avec insertion des randonnées (et mise à jour) à partir du dernier jeu de fichiers CSV (penser aussi à l'effacement)

## installation sur OVH

* installer le backup résultant sur OVH.
  * L'installation sur OVH de Drupal 8 reste bloquée ...

# 19/08/2016

## une seule activation

* **TODO: make the rif_imports module dependent from randonnee_de_journee module**
  * *randonnee_de_journee* module has itself a lot of dependent modules!

## la commande d'import plante

* see README.md file (à la racine)
  * Beaucoup de randonnées de journées on été importées
  * pourquoi la clé est encore -1 !!!

# 22/08/2016

* la présentation des heures n'est pas homogène dans la vue par défaut
* Ajouter le champs type
  * Il a été déjà défini dans le formulaire !!!
* tester la reprise sur des randonnées existantes
* créer le delete et tester

# 23/08/2016

* tester la reprise sur des randonnées existantes
* créer le delete et tester
* Attacher les randonnées à leurs animateurs
  * précondition: créer les animateurs ...
