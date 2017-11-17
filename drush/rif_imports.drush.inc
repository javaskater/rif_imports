<?php

use Drupal\rif_imports\Controller\ImportRjController;
use Drupal\rif_imports\Controller\ImportAdhController;

/**
 * @file
 * import/efface RIF Randos Jour Drush command
 */
function rif_imports_drush_command() {
    $items = array();

    $items['rif-import-randos-jours'] = array(
        'description' => 'Imports Days Hikes from a RIF csv File  into Drupal (Drupal 8 Rando-Journee content type)',
        'options' => array(
            'csv' => 'path of the csv file to import the day hikes from ...',
        ),
        'examples' => array(
            'drush rif-import-randos-jours --csv="~/RIF/importations/randonnees.csv"' => 'import day hikes from the specified csv file',
        ),
        'aliases' => array('rirj'),
    );

    $items['rif-efface-randos-jours'] = array(
        'description' => 'Remove Days Hikes (Drupal 8 Rando-Journee content type) contained in the csv file from Drupal',
        'options' => array(
            'csv' => 'path of the csv file where the day hikes to remove are specified ...',
        ),
        'examples' => array(
            'drush rif-efface-randos-jours --csv="~/RIF/importations/randonnees.eff.csv"' => 'remove day hikes specified the csv file',
        ),
        'aliases' => array('rerj'),
    );

    $items['rif-import-adherents'] = array(
        'description' => 'Imports Hikers from a RIF csv File  into Drupal (Drupal 8 Adhérent content type)',
        'options' => array(
            'csv' => 'path of the csv file to import the hikers from ...',
        ),
        'examples' => array(
            'drush rif-import-adherents --csv="~/RIF/importations/adherents.csv"' => 'import hikers from the specified csv file',
        ),
        'aliases' => array('riadh'),
    );

    $items['rif-efface-adherents'] = array(
        'description' => 'Remove Hikers (Drupal 8 Adhérent content type) contained in the csv file from Drupal',
        'options' => array(
            'csv' => 'path of the csv file where the day hikes to remove are specified ...',
        ),
        'examples' => array(
            'drush rif-efface-adherents --csv="~/RIF/importations/adherents.eff.csv"' => 'remove hikers specified the csv file',
        ),
        'aliases' => array('readh'),
    );

    /**
     * Helper command
     * Drush commands related to Entities.
     * see:http://drupal.stackexchange.com/questions/127939/how-to-list-all-available-entity-types
     */
    $items['rif-entities-list'] = array(
        'description' => dt("Show a list of available entities for the Randonneurs Ile de France."),
        'aliases' => array('rel'),
    );

    return $items;
}

/**
 * Drush callback to import Day Hikes.
 */
function drush_rif_imports_rif_import_randos_jours() {

    $path_file = drush_get_option("csv");
    drush_log(t('onva lire depui @csv', array('@csv' => $path_file)));

    $importer = new ImportRjController();


    // Get the hiking content to import
    $hikes_to_insert_or_update = $importer->insertOrUpdateDayHikes($path_file);
    //dlm($hikes_to_insert_or_update);

    //$node = node_load(55);
    //$batch = $importer->getContentUpdatetBatch($hikes_to_insert_or_update['dayhikes_to_update']);
    // Initialize the batch.
    //batch_set($batch);
    // Start the batch process.
    //drush_backend_batch_process();
}

/**
 * Drush callback to remove Day Hikes.
 */
function drush_rif_imports_rif_efface_randos_jours() {
    $path_file = drush_get_option("csv");
    drush_log(t('onva lire depui @csv', array('@csv' => $path_file)));

    $importer = new ImportRjController();


    // Get the hiking content to import
    $hikes_deleted = $importer->deleteDayHikes($path_file);
    if ($hikes_deleted){
        drush_log(t('from @tr dayhikes to remove, @rd effectively removed !!!', array('@tr' => $hikes_deleted['to_remove'], '@rd' => $hikes_deleted['removed'])), $type = 'ok');
    } else {
        drush_log(t('no dayhikes to remove !!!'), $type = 'warning');
    }
}

/**
 * Drush callback to import Hikers.
 */
function drush_rif_imports_rif_import_adherents() {
    $path_file = drush_get_option("csv");
    drush_log(t('onva lire depui @csv', array('@csv' => $path_file)));
    
    $importer = new ImportAdhController();

    $hikers_to_insert_or_update = $importer->insertOrUpdateHikers($path_file);
}

/**
 * Drush callback to remove Hikers.
 */
function drush_rif_imports_rif_efface_adherents() {
    $path_file = drush_get_option("csv");
    drush_log(t('onva lire depui @csv', array('@csv' => $path_file)));

    $importer = new ImportAdhController();

    $hikers_deleted = $importer->deleteHikers($path_file);
    if ($hikers_deleted){
        drush_log(t('from @tr hikers to remove, @rd effectively removed !!!', array('@tr' => $hikers_deleted['to_remove'], '@rd' => $hikers_deleted['removed'])), $type = 'ok');
    } else {
        drush_log(t('no dayhikes to remove !!!'), $type = 'warning');
    }
}

/**
 * Callback for the content-type-list command.
 * see:http://drupal.stackexchange.com/questions/127939/how-to-list-all-available-entity-types
 * Answer 2...
 */
function drush_rif_imports_rif_entities_list() {
    $entities = entity_content_types_list();
    print_r($entities);
}

/*
 * see Blog http://enzolutions.com/articles/2015/12/03/how-to-get-a-list-of-content-types-in-drupal-8/
 */

function entity_content_types_list() {
    
    $contentTypes = \Drupal::service('entity.manager')->getStorage('node_type')->loadMultiple();

    $contentTypesList = [];
    foreach ($contentTypes as $contentType) {
        $contentTypesList[$contentType->id()] = $contentType->label();
    }

    return $contentTypesList;
}
