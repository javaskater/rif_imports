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
    public function getDayHikesToInsertOrUpdate($path_file) {
        $dayHikes = array();
        if (($handle = $this->openfile($path_file)) !== FALSE) { //TODO à remplacer par une 
            $myDayHike = new DayHike();
            $this->mappingImport = array(//Certains se rapprochent de la DayHike et d'autres du TrainRide ...
                array('field' => 'cle', 'csv_pos' => O, 'attribute' => array('cle')),
                array('field' => 'body', 'csv_pos' => 22, 'attribute' => array('itineraire')),
                array('field' => 'title', 'csv_pos' => 4, 'attribute' => array('titre')),
                array('field' => 'field_date', 'csv_pos' => 1, 'attribute' => array('date')),
                /*array('field' => 'field_gare_depart_aller', 'csv_pos' => 19, 'attribute' => array('aller','gareDepart')),
                array('field' => 'field_heure_depart_aller', 'csv_pos' => 11, 'attribute' => array('aller','heureDepart')),
                array('field' => 'field_heure_depart_aller', 'csv_pos' => 21, 'attribute' => array('aller','gareArrivee')),
                array('field' => 'field_heure_arrivee_aller', 'csv_pos' => 14, 'attribute' => array('aller','heureArrivee')),
                array('field' => 'field_gare_depart_retour', 'csv_pos' => 23, 'attribute' => array('retour','gareDepart')),
                array('field' => 'field_heure_depart_retour', 'csv_pos' => 15, 'attribute' => array('retour','heureDepart')),
                array('field' => 'field_gare_arrivee_retour', 'csv_pos' => 25, 'attribute' => array('retour','gareArrivee')),
                array('field' => 'field_heure_arrivee_retour', 'csv_pos' => 18, 'attribute' => array('retour','heureArrivee'))*/);
            $nodes_to_insert = [];
            $nodes_to_update = [];
            $imported = 0;
            $row = 0;
            while (($data = fgetcsv($handle)) !== FALSE) {
                $num = count($data);
                drush_log(t('- @num champs à la ligne @row: ', array('@num' => $num, '@row' => $row)));
                if ($row > 0) { //the first line is titles'line !!!
                    for ($c = 0; $c < $num; $c++) {
                        foreach ($this->mappingImport as $csv_map) {
                            if ($csv_map['csv_pos'] == $c) {
                                drush_log(t('++ on va entrer pour la position @c la valeurvaleur @data: ', array('@c' => $c, '@data' => $data[$c])));
                                //$attribute_to_set=$myDayHike;
                                /*foreach($csv_map['attribute'] as $attr_string){
                                    $attribute_to_set=$attribute_to_set->$attr_string;
                                } */ 
                                //$attribute_to_set=$data[$c];
                                $attribute_to_set=$csv_map['attribute'][0];
                                $myDayHike->$attribute_to_set=$data[$c];
                                dlm($attribute_to_set);
                            }
                        }
                    }
                    $imported ++;
                }
                $row++;
                drush_log(t('++ ligne @imported avec succes: ', array('@imported' => $imported)));
                $dayHikes[] = $myDayHike;
                /*
                 * 
                 */
            }
            fclose($handle);
            drush_log(t('There were @nombre randonnées de journée successfully imported!', array('@nombre' => $imported)), $type = 'ok');
            dlm($dayHikes);
            return array('dayhikes_to_insert' => $nodes_to_insert, 'dayhikes_to_update' => $nodes_to_update);
        }
        return false;
        // Delete content by content type.
        /* if ($content_types !== FALSE) {
          $nodes_to_delete = array();
          foreach ($content_types as $content_type) {
          if ($content_type) {
          $nids = $this->connection->select('node', 'n')
          ->fields('n', array('nid'))
          ->condition('type', $content_type)
          ->execute()
          ->fetchCol('nid');

          $nodes_to_delete = array_merge($nodes_to_delete, $nids);
          }
          }
          }
          // Delete all content.
          else {
          $nodes_to_delete = FALSE;
          }

          return $nodes_to_delete; */
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
