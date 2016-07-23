<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Controller\ImportController.
 */

namespace Drupal\rif_imports\Controller;

use Drupal\rif_imports\Controller\ImportControllerBase;

/**
 * Returns responses for devel module routes.
 */
class ImportRjController extends ImportControllerBase {

    protected $mapping;
    protected $randoJour;

    public function __construct() {
        parent::__construct();
        //A mettre dans la classe RandosJour ???
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
            return array('dayhikes_to_insert' =>  $nodes_to_insert, 'dayhikes_to_update' => $nodes_to_update);
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
