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
