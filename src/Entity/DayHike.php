<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Entity\ImportController.
 */

namespace Drupal\rif_imports\Entity;

use Drupal\rif_imports\Entity\TrainRide;

class DayHike {
    /*
     * D8's machine name custom entity type !!!
     */

    public static $d8_custom_entity_type = 'randonnee_de_journee';
    /*
     * field is the machine name ot the field int the D8's custom entity type
     * csv_pos is the column number (starting with 0) int the CSV file's line where the corresponding information is to be found
     * attribute: is the attribute name in this object.... If the arrays length is 2, 
     * - the first name is the name of the TrainRide Object (see constructor)
     * - the second name is the name of the attribute of the preceding TrainRide Object
     */
    public static $d8_csv_mapping = array(//Certains se rapprochent de la DayHike et d'autres du TrainRide ...
        array('field' => 'cle', 'csv_pos' => O, 'attribute' => array('cle')),
        array('field' => 'body', 'csv_pos' => 22, 'attribute' => array('itineraire')),
        array('field' => 'title', 'csv_pos' => 4, 'attribute' => array('titre')),
        array('field' => 'field_date', 'csv_pos' => 1, 'attribute' => array('date')),
        array('field' => 'field_gare_depart_aller', 'csv_pos' => 19, 'attribute' => array('aller', 'gareDepart')),
        array('field' => 'field_heure_depart_aller', 'csv_pos' => 11, 'attribute' => array('aller', 'heureDepart')),
        array('field' => 'field_heure_depart_aller', 'csv_pos' => 21, 'attribute' => array('aller', 'gareArrivee')),
        array('field' => 'field_heure_arrivee_aller', 'csv_pos' => 14, 'attribute' => array('aller', 'heureArrivee')),
        array('field' => 'field_gare_depart_retour', 'csv_pos' => 23, 'attribute' => array('retour', 'gareDepart')),
        array('field' => 'field_heure_depart_retour', 'csv_pos' => 15, 'attribute' => array('retour', 'heureDepart')),
        array('field' => 'field_gare_arrivee_retour', 'csv_pos' => 25, 'attribute' => array('retour', 'gareArrivee')),
        array('field' => 'field_heure_arrivee_retour', 'csv_pos' => 18, 'attribute' => array('retour', 'heureArrivee')));


    /*
     * managing dynamic attributes through custom getters and setters 
     * see paragraph 7.11 of PHP In Action
     * OReilly Books ...
     */
    protected $__data = array('cle' => false, 'itineraire' => false, 'titre' => false,
        'date' => false, 'aller' => false, 'retour' => false);

    public function __construct() {
        $this->__data['aller'] = new TrainRide();
        $this->__data['retour'] = new TrainRide();
    }

    /*
     * managing dynamic attributes through custom getters and setters
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

    /*
     * managing dynamic attributes through custom getters and setters
     * see paragraph 7.11 of PHP In Action
     * OReilly Books ...
     */

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

    /*
     * Looks for if the DayHike already exists in Drupal 8 Public Website...
     * we want to check an instance if the  if the cle attribute
     * playing with entities see :
     * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Entity%21entity.api.php/group/entity_api/8.2.x
     */

    public function d8Exists() {
        if ($this->__data['cle']) {
            $searchedDayHike = \Drupal::entityQuery(self::$d8_custom_entity_type)
                    ->condition('cle', $this->__data['cle'], '==')
                    ->execute();
            return $searchedDayHike;
        } else {
            return false;
        }
    }

    /*
     * If the DayHike does not already exists (nid==false) 
     * then we  want to insert it as a new DayHike
     * else if it does exist (nid is an >0 integer)
     * then we update the actual hike ..
     * creating entitites, comment 2 at :
     * http://stackoverflow.com/questions/24172791/how-to-programmatically-create-a-node-in-drupal-8
     * entity_create (see core/include/entity.inc)  is deprecated in favor
     * of Drupal::entityManager/Storage
     */
    public function d8InsertOrUpdate($nid = false) {
        $insertedOrUpdated = array('nid' => $nid, 'd8Entity' => false);
        $storage_manager = \Drupal::entityManager()->getStorage(self::$d8_custom_entity_type);
        $new_dayhike_values = array('nid' => $nid);
        foreach (self::$d8_csv_mapping as $map_entry) {
            if (count($map_entry['attribute']) == 1) {
                $attribute_to_get =  $map_entry['attribute'][0];
                $new_dayhike_values[$map_entry['field']] = $this->$attribute_to_get;
            } else if (count($map_entry['attribute']) == 2) {
                $object_to_set = $map_entry['attribute'][0];
                $attribute_to_get = $map_entry['attribute'][1];
                $new_dayhike_values[$map_entry['field']] = $this->$object_to_set->$attribute_to_get = $data[$c];
            }
        }
        $new_day_hike = $storage_manager->create($new_dayhike_values);
        $d8_entity = $new_day_hike->save();
        $insertedOrUpdated['d8Entity'] = $d8_entity;
        return $insertedOrUpdated;
    }

}
