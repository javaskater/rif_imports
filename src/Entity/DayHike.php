<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Entity\ImportController.
 */

namespace Drupal\rif_imports\Entity;

use Drupal\rif_imports\Entity\TrainRide;

class DayHike {

    protected $__data = array('cle' => false, 'itineraire' => false, 'titre' => false,
        'date' => false, 'aller' => false, 'retour' => false);

    public function __construct() {
        $this->__data['aller'] = new TrainRide();
        $this->__data['retour'] = new TrainRide();
    }

    /*
     * see paragraph 7.11 of PHP In Action
     * OReilly Books ...
     */

    public function __get($property) {
        if (isset($this->__data[$property])) {
            return $this->__data[$property];
        } else {
            return false;
        }
    }

    public function __set($property, $value) {
        $this->__data[$property] = $value;
        if ($property == 'date') {
            $property_to_set = 'jourDeplacement';
            $this->__data['aller']->$property_to_set = $value;
            $this->__data['retour']->$property_to_set = $value;
            //dlm($this->__data['aller']);
            //dlm($this->__data['retour']);
        }
    }

}
