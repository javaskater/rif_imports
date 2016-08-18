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

  * To active the just downloaded *rif_imports* module, just run the following command:
``̀  bash

``̀


## Add the Randonnée de Jour Custom Type and its views
* For the purpose you just has to activate the embedded *randonnee_de_journee* module (module previously created by feature on a first working drupal 8 installation)
* __STILL to be explained__

## Running the SHELL

* The following command which can be placed in a CRON has to be run:
``` bash
#the -v option at the end is to allow the NOTICE drush_log messages to be printed out
jpmena@jpmena-P34 ~ $ drush @d8rif.dev rirj --csv="~/RIF/importations/randonnees.csv" -v >d.log 2>&1
## the @d8rif.dev drush shortcut had been defined the following way :
### in this example /home/jpmena/RIF/d8rif is the $DRUPAL_HOME
#### and http://dru8rif.ovh is the URL for accessing the drupal website on our LAMP
jpmena@jpmena-P34 ~ $ cat .drush/site-aliases/d8rif.aliases.drushrc.php
<?php
$aliases['dev'] = array(
   'root' => '/home/jpmena/RIF/d8rif',
   'uri' => 'http://dru8rif.ovh',
);
/**
 * $aliases['live'] = array(
 * 'root' => '/kunden/homepages/21/d462702613/htdocs/sites_jpm/d8jpmena',
 * 'uri' => 'http://jpmena.eu',
 * );
 **/
```
