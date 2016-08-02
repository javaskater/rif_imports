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

## Things to remember:
* Along the way there are many things I have to remember that is why I need a [TODOs List](docs/TODO.md)
