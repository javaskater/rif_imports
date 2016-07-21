<?php

use Drupal\rif_imports\Controller\ImportRjController;

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
            'drush rif-import-randos-jours csv="~/RIF/importations/randonnees.csv"' => 'import day hikes from the specified file',
        ),
        'aliases' => array('rirj'),
    );

    $items['rif-efface-randos-jours'] = array(
        'description' => 'Remove Days Hikes (Drupal 8 Rando-Journee content type) contained in the csv file from Drupal',
        'options' => array(
            'csv' => 'path of the csv file where the day hikes to remove are specified ...',
        ),
        'examples' => array(
            'drush rif-efface-randos-jours csv="~/RIF/importations/randonnees.eff.csv"' => 'remove day hikes specified the csv file',
        ),
        'aliases' => array('rerj'),
    );
    return $items;
}

/**
 * Drush callback to import Day Hikes.
 */
function drush_rif_imports_rif_import_randos_jours() {
    
    $importer = new ImportRjController();

    if (($handle = $importer->openfile()) !== FALSE) {
        $imported = 0;
        $row = 1;
        while (($data = fgetcsv($handle)) !== FALSE) {
            $num = count($data);
            drush_log(t('- @num champs à la ligne @row: ', array('@num' => $num, '@row' => $row)));
            for ($c = 0; $c < $num; $c++) {
                drush_log(t('++ le champs @c pour valeur @data: ', array('@c' => $c, '@data' => $data[$c])));
            }
            $imported ++;
            $row++;
        }
        fclose($handle);
        drush_log(t('There were @nombre randonnées de journée successfully imported!', array('@nombre' => $imported)), $type = 'ok');
    }
}

/**
 * Drush callback to remove Day Hikes.
 */
function drush_rif_imports_rif_efface_randos_jours() {
    $effaces = 0;
    drush_log(t('supprimé avec succès @nombre randonnées de journée !', array('@nombre' => $effaces)), $type = 'ok');
}
