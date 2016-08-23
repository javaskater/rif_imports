# Drupal 8 Module

## Target/History

## orgin of the need

* The [Randonneurs Ile de France](http://rifrando.fr/) is a non profit organisation specialized in hiking in [Ile de France](https://en.wikipedia.org/wiki/%C3%8Ele-de-France)...
* The [public web site](http://rifrando.fr/) is completed by a site reserved for member (Site Adhérent)...
* The idea here is to propose a unique WebSite using the CMF capabilities of Drupal
  * and the [Symphony](https://symfony.com/) oriented extensibility of [Drupal8](https://www.drupal.org/8) in particular ...

## Ressources

* The code has been strongly inspired by the [Drupal Delete All Module](https://www.drupal.org/project/delete_all)
* for handling with batches, I started [page about batches](docs/BATCH.md)

# Other Topics

## Inserting the way without batch
* checking Entities and if those entities exists see [Drupal 8.2 Entity api](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Entity%21entity.api.php/group/entity_api/8.2.x)

``` php
$fids = Drupal::entityQuery('file')
->condition('status', FILE_STATUS_PERMANENT, '<>')
->condition('changed', REQUEST_TIME - $age, '<')
->range(0, 100)
->execute();
```
## Complementary issues:

### How I imported the custom content type from an existing installation:

* importing the configuration of my randonnee de Jour Content Type is on the [DAY HIKE IMPORT PAGE](docs/IMPORTS.md)

## Things to remember:
* Along the way there are many things I have to remember that is why I need a [TODOs List](docs/TODO.md)

# Running the project:

## install a brand new Drupal 8 in french

* the [Randonneurs Ile de France](http://rifrando.fr) is a french hiking association.
* so I provided with the module a utility script which install a [Drupal 8](https://www.drupal.org/8) from scratch along with the french translation and the french locale as default
* that script is to be found at [*rif_imports/scripts/CMS/Drupal/installNewD8.sh*](https://github.com/javaskater/rif_imports/blob/master/scripts/CMS/Drupal/installNewD8.sh)
* for an example and the result of that command see the [Automatic installation section](docs/INSTALL.md)

## install a global Drush

* I recommend a global drush installation as I explained on my [WIKI Page (sorry in french for the moment)](http://wiki.jpmena.eu/index.php?title=Php:drupal8:drush:installation:igpde)

## Activate the rif_imports module into your fresh Drupal installation

* The [*rif_imports*  project](https://github.com/javaskater/rif_imports) has to be downloaded into your *$DRUPAL_HOME/modules*
  * on way to do this is via the administrative interface (_Download zip_)
  * another way is to use a git command:
    * *git clone https://github.com/javaskater/rif_imports.git*

  * To active the just downloaded *rif_imports* module, just run the following command *drush --root=$DRUPAL_HOME en -y rif_imports*

* At the end we should have passed the following commands:
  * Cloning [rif_imports](https://github.com/javaskater/rif_imports) inside the *$DRUPAL_HOME/modules*

``` bash
jpmena@jpmena-HP ~/RIF $ export DRUPAL_HOME=$HOME/RIF/d8rif
#we clone the project in our drupal module directory
jpmena@jpmena-HP ~/RIF $ cd $DRUPAL_HOME/modules
jpmena@jpmena-HP ~/RIF/d8rif/modules $ git clone https://github.com/javaskater/rif_imports.git
Clonage dans 'rif_imports'...
remote: Counting objects: 196, done.
remote: Total 196 (delta 0), reused 0 (delta 0), pack-reused 196
Réception d\'objets: 100% (196/196), 79.11 KiB | 0 bytes/s, fait.
Résolution des deltas: 100% (93/93), fait.
Vérification de la connectivité... fait.
jpmena@jpmena-HP ~/RIF/d8rif/modules $ cd -
/home/jpmena/RIF
```

  * Activating *$DRUPAL_HOME/modules/rif_imports* module via the global **drush**:

``` bash
#we activate the module
jpmena@jpmena-HP ~/RIF $ drush --root=$DRUPAL_HOME en -y rif_imports
The following projects have unmet dependencies:                                                                                                     [ok]
rif_imports requires delete_all
Would you like to download them? (y/n): y
Project delete_all (8.x-1.0-alpha1) downloaded to /home/jpmena/RIF/d8rif//modules/delete_all.                                                       [success]
The following extensions will be enabled: rif_imports, delete_all, serialization, rest, randonnee_de_journee
Do you really want to continue? (y/n): y
delete_all was enabled successfully.                                                                                                                [ok]
randonnee_de_journee was enabled successfully.                                                                                                      [ok]
rest was enabled successfully.                                                                                                                      [ok]
rest defines the following permissions: restful delete entity:node, restful get entity:node, restful patch entity:node, restful post entity:node
rif_imports was enabled successfully.                                                                                                               [ok]
serialization was enabled successfully.                                                                                                             [ok]
```

### enabling the *rif_imports* module has added the *Randonnée de Jour* Custom Type and its views
* *rif_imports* activation activated also the embedded *randonnee_de_journee* module (module previously created by feature on a first working drupal 8 installation)
  * so we don't have to activate *rif_imports/dependencies/custom/randonnee_de_journee* by ourselves:
    * as the directory *randonnee_de_journee* is already under the *module* directory, drush has no problem seing it and activating it as a dependant module like *rest* for example...
  * *rif_imports/dependencies/custom/randonnee_de_journee* module has itself a lot of dependent modules!
    * the Drupal8 rest modules are automatically downloaded and then activated as the custom type *randonnee_de_journee* had been initially defined with a rest services' view (a view for web's rest services).

* **NB** : If *rif_imports/dependencies/custom/randonnee_de_journee* was not automatically activated as dependent *rif_imports* dependant module we would have had to pass the following command:

``` bash
jpmena@jpmena-HP ~/RIF $ drush --root=$DRUPAL_HOME en -y randonnee_de_journee
The following extensions will be enabled: randonnee_de_journee, serialization, rest
Do you really want to continue? (y/n): y
randonnee_de_journee was enabled successfully.                                                                                                      [ok]
rest was enabled successfully.                                                                                                                      [ok]
rest defines the following permissions: restful delete entity:node, restful get entity:node, restful patch entity:node, restful post entity:node
serialization was enabled successfully.                                                                                                             [ok]
```

## Running the SHELL

* We just hav to run the first drush command imported by the module *rif_imports* which is:
  * underneath is the corresponding extract of *$DRUPAL_HOME/modules/rif_imports/drush/rif_imports.drush.inc*

``` php
function rif_imports_drush_command() {
    $items = array();

    $items['rif-import-randos-jours'] = array(
        'description' => 'Imports Days Hikes from a RIF csv File  into Drupal (Drupal 8 Rando-Journee content type)',
        'options' => array(
            'csv' => 'path of the csv file to import the day hikes from ...',
        ),
        'examples' => array(
            'drush rif-import-randos-jours csv="~/RIF/importations/randonnees.csv"' => 'import day hikes from the specified csv file',
        ),
        'aliases' => array('rirj'),
    );
```

* So we will use the __rirj__ drush command with the path to the csv file containing the Day's Hikes to be imported:
  * the module comes with sample csv files, so we will use theis for this example

``` bash
jpmena@jpmena-HP ~/RIF $ export DRUPAL_HOME=$HOME/RIF/d8rif
jpmena@jpmena-HP ~/RIF $ drush --root=$DRUPAL_HOME rirj --csv=$DRUPAL_HOME/modules/rif_imports/examples/csvfiles/randonnees.sample.csv
```
* randonnees.sample.csv has only 2 lines... So I imported only two day-hikes!!!

### enabling the *rif_imports* module has also downloaded and enabled the [*delete_all*](https://www.drupal.org/project/delete_all) module

* I decided to define the [*delete_all*](https://www.drupal.org/project/delete_all) module as a dependency of my *rif_imports* module (like for *randonnee_de_journee*)!
  * the content of module's definition file (*rif_imports/rif_imports.info.yml*) is in fact

``` yaml
type: module
name: Imports RIF's Hikes insite Drupal
description: Update the RIF's Drupal with the csv exports of the RIF's ACCESS Database
package: Development
# core: '8.x'

# Information added by Drupal.org packaging script on 2016-05-16
version: '8.x-0.1-alpha1'
core: '8.x'
project: 'rif-imports'

#the content type definition is one of the dependencies (relative path dependencies/custom/randonnee_de_journee)
## I need the content type randonnee_de_journee to be defined before importing the corresponding entities
#delete_all is another dependency as it allow me to delete the node of type Randonnée de Journée
## through the command: drush @d8rif.dev dadc randonnee_de_journee
dependencies:
  - randonnee_de_journee
  - delete_all
```

* Target of the [delete_all](https://www.drupal.org/project/delete_all) module:
   * empty all the users
   * empty  all the content of a custom entity type!
* I want to empty all the content of type *randonnee_de_journee* (reinitiate my RIF's Drupal8 with fresh content):
  * to that purpose, I only have to pass the following comand:

   ``` bash
   jpmena@jpmena-HP ~/RIF $ drush --root=$DRUPAL_HOME dadc --type randonnee_de_journee
   Deleting node with nid 1                                                                                                                            [ok]
   Deleting node with nid 2                                                                                                                            [ok]
   Deleted 2 nodes.                                                                                                                                    [status]
   ```
* As I previously imported only 2 dayhikes, there are now only 2 dayhikes to be removed (2 nodes deleted)
