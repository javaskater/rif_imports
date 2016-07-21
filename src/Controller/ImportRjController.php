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

    public function __construct() {
        parent::__construct();
        //A mettre dans la classe RandosJour ???
        $this->mapping = array(['body', 22, 'string'], ['title', 4, 'string'], ['field_date', 1, 'rifdate'],
            ['field_gare_depart', 19, 'string'], ['field_heure_depar', 11, 'riftime'],
            ['field_gare_depart_retour', 23, 'string'], ['field_heure_arrivee_aller', 15, 'riftime']);
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
    public function getContentToDelete($content_types = FALSE) {
        $nodes_to_delete = [];

        // Delete content by content type.
        if ($content_types !== FALSE) {
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

        return $nodes_to_delete;
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
