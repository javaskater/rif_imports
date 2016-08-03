<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Controller\ImportController.
 */

namespace Drupal\rif_imports\Controller;

use Drupal\rif_imports\Controller\ImportControllerBase;
use Drupal\rif_imports\Entity\DayHike;

/**
 * Returns responses for devel module routes.
 */
class ImportRjController extends ImportControllerBase {

    protected $mappingImport;
    protected $randoJour;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get nids of the nodes to delete.
     *
     * @param array $roles
     *   Array of roles.
     *
     * @return array
     *   Array of nids of nodes to delete.
     */
    public function insertOrUpdateDayHikes($path_file) {
        $dayHikes = array();
        if (($handle = $this->openfile($path_file)) !== FALSE) { //TODO à remplacer par une 
            $myDayHike = new DayHike();
            $this->mappingImport = DayHike::$d8_csv_mapping;
            $nodes_inserted = [];
            $nodes_updated = [];
            $treated = 0;
            $row = 0;
            while (($data = fgetcsv($handle)) !== FALSE) {
                $num = count($data);
                drush_log(t('- @num champs à la ligne @row: ', array('@num' => $num, '@row' => $row)));
                if ($row > 0) { //the first line is titles'line !!!
                    for ($c = 0; $c < $num; $c++) {
                        foreach ($this->mappingImport as $csv_map) {
                            if ($csv_map['csv_pos'] == $c) {
                                drush_log(t('++ on va entrer pour la position @c la valeurvaleur @data: ', array('@c' => $c, '@data' => $data[$c])));
                                if (count($csv_map['attribute']) == 1) {
                                    $attribute_to_set = $csv_map['attribute'][0];
                                    $myDayHike->$attribute_to_set = $data[$c];
                                } else if (count($csv_map['attribute']) == 2) {
                                    $object_to_set = $csv_map['attribute'][0];
                                    $attribute_to_set = $csv_map['attribute'][1];
                                    $myDayHike->$object_to_set->$attribute_to_set = $data[$c];
                                    dlm($object_to_set);
                                }
                            }
                        }
                    }
                    $treated ++;
                }
                $row++;
                //drush_log(t('++ ligne @treated avec succes: ', array('@treated' => $treated)));
                $dayHikes[] = $myDayHike;
                $testD8DayHike = $myDayHike->d8Exists();
                dlm($testD8DayHike);
                $node_id=false;
                if($testD8DayHike){
                    $node_id=$testD8DayHike->nid;
                }
                $finalD8DayHike = $myDayHike->d8InsertOrUpdate($node_id);
                dlm($finalD8DayHike);
                if ($finalD8DayHike['nid']) {
                    $nodes_updated[] = $finalD8DayHike['d8Entity'];
                } else {
                    $nodes_inserted[] = $finalD8DayHike['d8Entity'];
                }
            }
            fclose($handle);
            //drush_log(t('There were @nombre randonnées de journée successfully imported!', array('@nombre' => $imported)), $type = 'ok');
            //dlm($dayHikes);
            return array('collected_dayhikes'=> $dayHikes, 'new_d8_entities' => $nodes_inserted, 'updated_d8_entities' => $nodes_updated);
        }
        return false;
    }

    /**
     *
     */
    public function getContentDeleteBatch($nodes_to_delete = FALSE) {
        // Define batch.
        $batch = array(
            'operations' => array(
                array('delete_all_content_batch_delete', array($nodes_to_delete)),
            ),
            'finished' => 'delete_all_content_batch_delete_finished',
            'title' => t('Deleting users'),
            'init_message' => t('User deletion is starting.'),
            'progress_message' => t('Deleting users...'),
            'error_message' => t('User deletion has encountered an error.'),
        );

        return $batch;
    }

}
