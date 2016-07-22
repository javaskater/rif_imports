<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Entity\ImportController.
 */

namespace Drupal\rif_imports\Entity;

class RandoJour
{
    protected $mappingImport;
    protected $itineraire;
    protected $titre;
    protected $date;
    protected $aller;
    protected $retour;


    public function __construct() {
        parent::__construct();
        $this->mappingImport = array(
            array('field' => 'body', 'csv_pos' => 22, 'setter' => 'itineraire'), 
            array('field' => 'title', 'csv_pos' => 4, 'attribute' => 'titre'), 
            array('field' => 'field_date', 'csv_pos' => 1, 'attribute' => 'date'),
            array('field' => 'field_gare_depart', 'csv_pos' =>  19, 'attribute' => 'departGareDepart'), 
            array('field' => 'field_heure_depar', 'csv_pos' =>  11, 'attribute' => 'riftime'),
            array('field' =>  'field_gare_depart_retour', 'csv_pos' =>  23, 'attribute' => 'retourGareDepart'), 
            array('field' =>  'field_heure_arrivee_aller', 'csv_pos' =>  15, 'attribute' => 'riftime'));
        
    }
    
}
