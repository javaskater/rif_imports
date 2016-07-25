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
        if (($handle = $this->openfile()) !== FALSE) { //TODO à remplacer par une 
            $myDayHike = new DayHike();
            $this->mappingImport = array(//Certains se rapprochent de la DayHike et d'autres du TrainRide ...
                array('field' => 'body', 'csv_pos' => 22, 'attribute' => $myDayHike->itineraire),
                array('field' => 'title', 'csv_pos' => 4, 'attribute' => $myDayHike->titre),
                array('field' => 'field_date', 'csv_pos' => 1, 'attribute' => $myDayHike->date),
                array('field' => 'field_gare_depart_aller', 'csv_pos' => 19, 'attribute' => $myDayHike->aller->gareDepart),
                array('field' => 'field_heure_depart_aller', 'csv_pos' => 11, 'attribute' => $myDayHike->aller->heureDepart),
                array('field' => 'field_heure_depart_aller', 'csv_pos' => 21, 'attribute' => $myDayHike->aller->gareArrivee),
                array('field' => 'field_heure_arrivee_aller', 'csv_pos' => 14, 'attribute' =>  $myDayHike->aller->heureArrivee),
                array('field' => 'field_gare_depart_retour', 'csv_pos' => 23, 'attribute' => $myDayHike->retour->gareDepart),
                array('field' => 'field_heure_depart_retour', 'csv_pos' => 15, 'attribute' => $myDayHike->retour->heureDepart),
                array('field' => 'field_gare_arrivee_retour', 'csv_pos' => 25, 'attribute' => $myDayHike->retour->gareArrivee),
                array('field' => 'field_heure_arrivee_retour', 'csv_pos' => 18, 'attribute' => $myDayHike->retour->heureArrivee));
            $nodes_to_insert = [];
            $nodes_to_update = [];
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
