<?php

/* 
 * Can be launched by
 * drush --root=$DRUPAL_HOME scr  $DRUPAL_HOME/modules/rif_imports/drush/tests/DayHikeQueryTest.php -v
 * to ask for nodes see https://api.drupal.org/api/drupal/core!lib!Drupal.php/function/Drupal%3A%3AentityQuery/8.2.x
 * If something goes wrong during request then 
 */


$entity_type = 'randonnee_de_journee';

$query = \Drupal::entityQuery('node');

//drush_log(t('found @c nodes', array('c'=> strval($query->count()))));
$hike_nids = $query->condition('field_cle',31018,'=')->execute();

foreach ($hike_nids as $hike_nid){
    drush_log(t('found node with id : @id', array('@id'=> $hike_nid)));
}


