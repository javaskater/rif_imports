<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Entity\ImportController.
 */

namespace Drupal\rif_imports\Entity;
use Drupal\rif_imports\Entity\TrainRide;

class DayHike{
    public $itineraire;
    public $titre;
    public $date;
    public $aller;
    public $retour;


    public function __construct() {
        parent::__construct();
        $this->aller = new TrainRide();
        $this->retour = new TrainRide();
    }
    
}
