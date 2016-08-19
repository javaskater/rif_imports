# Drupal 8 Module
## Target/History

## orgin of the need

* The [Randonneurs Ile de France](http://rifrando.fr/) is a non profit organisation specialized in hiking in [Ile de France](https://en.wikipedia.org/wiki/%C3%8Ele-de-France)...
* The [public web site](http://rifrando.fr/) is completed by a site reserved for member (Site Adhérent)...
* The idea here is to propose a unique WebSite using the CMF capabilities of Drupal
  * and the [Symphony](https://symfony.com/) oriented extensibility of [Drupal8](https://www.drupal.org/8) in particular ...

## Ressources

* The code has been strongly inspired by the [Drupal Delete All Module](https://www.drupal.org/project/delete_all)
* for handdling with batches, I started [page about batches](docs/BATCH.md)

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
* so I provided with the module a utility script which install a Drupal8 from scratch along with the french translation and the french locale as default
* that script is to be found at [*rif_imports/scripts/CMS/Drupal/installNewD8.sh*](https://github.com/javaskater/rif_imports/blob/master/scripts/CMS/Drupal/installNewD8.sh)

## install a global Drush

* I recommend a global drush installation as I explained on my [WIKI Page (sorry in french for the moment)](http://wiki.jpmena.eu/index.php?title=Php:drupal8:drush:installation:igpde)

## Activate the rif_imports module into your fresh Drupal installation

* The [*rif_imports*  project](https://github.com/javaskater/rif_imports) has to be downloaded into your *$DRUPAL_HOME/modules*
  * on way to do this is via the administrative interface (_Download zip_)
  * another way is to use a git command:
    * *git clone https://github.com/javaskater/rif_imports.git*

  * To active the just downloaded *rif_imports* module, just run the following command *drush --root=$DRUPAL_HOME en -y rif_imports*

* at the end we should have passed the following commands:

``̀  bash
jpmena@jpmena-HP ~/RIF $ export DRUPAL_HOME=$HOME/RIF/d8rif
#we Download the module form GITHUB
jpmena@jpmena-HP ~/RIF $ cd $DRUPAL_HOME/modules
jpmena@jpmena-HP ~/RIF/d8rif/modules $ git clone https://github.com/javaskater/rif_imports.git
Clonage dans 'rif_imports'...
remote: Counting objects: 196, done.
remote: Total 196 (delta 0), reused 0 (delta 0), pack-reused 196
Réception d'objets: 100% (196/196), 79.11 KiB | 0 bytes/s, fait.
Résolution des deltas: 100% (93/93), fait.
Vérification de la connectivité... fait.
jpmena@jpmena-HP ~/RIF/d8rif/modules $ cd -
/home/jpmena/RIF
#we activate the module
jpmena@jpmena-HP ~/RIF $ drush --root=$DRUPAL_HOME en -y rif_imports
The following extensions will be enabled: rif_imports
Do you really want to continue? (y/n): y
rif_imports was enabled successfully.                                                                                                               [ok]
``̀


## Add the Randonnée de Jour Custom Type and its views
* For the purpose you just has to activate the embedded *randonnee_de_journee* module (module previously created by feature on a first working drupal 8 installation)
* we have just to activate : *modules/rif_imports/dependencies/custom/randonnee_de_journee*
  * as the directory *randonnee_de_journee* is already under the *module* directory, drush has no problem seing it ....
* **TODO: make the rif_imports module dependent from randonnee_de_journee module**
  * *randonnee_de_journee* module has itself a lot of dependent modules!
* so we just have pass the following command:

``̀  bash
jpmena@jpmena-HP ~/RIF $ drush --root=$DRUPAL_HOME en -y randonnee_de_journee
The following extensions will be enabled: randonnee_de_journee, serialization, rest
Do you really want to continue? (y/n): y
randonnee_de_journee was enabled successfully.                                                                                                      [ok]
rest was enabled successfully.                                                                                                                      [ok]
rest defines the following permissions: restful delete entity:node, restful get entity:node, restful patch entity:node, restful post entity:node
serialization was enabled successfully.                                                                                                             [ok]

``̀

* the Drupal8 rest modules are automatically downloaded and then activated as the custom type comes with rest services' views .

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
jpmena@jpmena-HP ~ $ drush --root=$DRUPAL_HOME rirj --csv=$DRUPAL_HOME/modules/rif_imports/examples/csvfiles/randonnees.csv
Invalid argument supplied for foreach() batch.inc:47                                                                                                [warning]
Error: Call to a member function claimItem() on null in _drush_batch_worker() (line 146 of                                                          [error]
phar:///usr/local/bin/drush/commands/core/drupal/batch.inc).
Error: Call to a member function claimItem() on null in phar:///usr/local/bin/drush/commands/core/drupal/batch.inc on line 146
Error: Call to a member function claimItem() on null in _drush_batch_worker() (line 146 of phar:///usr/local/bin/drush/commands/core/drupal/batch.inc).
Drush command terminated abnormally due to an unrecoverable error.                                                                                  [error]
```

* TODO correct mistake during command !!!
