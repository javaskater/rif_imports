<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Entity\ImportController.
 */

namespace Drupal\rif_imports\Entity;

use Drupal\node\Entity\Node;

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
        array('field' => 'field_cle', 'csv_pos' => O, 'attribute' => array('cle')),
        array('field' => 'field_type', 'csv_pos' => 2, 'attribute' => array('type')),
        array('field' => 'body', 'csv_pos' => 22, 'attribute' => array('itineraire')),
        array('field' => 'title', 'csv_pos' => 4, 'attribute' => array('titre')),
        array('field' => 'field_date', 'csv_pos' => 1, 'attribute' => array('date')),
        array('field' => 'field_gare_depart_aller', 'csv_pos' => 19, 'attribute' => array('aller', 'gareDepart')),
        array('field' => 'field_heure_depart_aller', 'csv_pos' => 11, 'attribute' => array('aller', 'heureDepart')),
        array('field' => 'field_gare_arrivee_aller', 'csv_pos' => 21, 'attribute' => array('aller', 'gareArrivee')),
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
    protected $__data = array('cle' => false, 'type' => false, 'itineraire' => false, 'titre' => false,
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
        $result = false;
        if (isset($this->__data[$property])) {
            $result = $this->__data[$property];
        }
        //drush_log(t('from DayHike Entity, getting @p the value obtained is @v',array('@p'=>$property,'@v'=>$result)));
        return $result;
    }

    /*
     * managing dynamic attributes through custom getters and setters
     * see paragraph 7.11 of PHP In Action
     * OReilly Books ...
     */

    public function __set($property, $value) {
        //drush_log(t('from DayHike Entity, setting @p with @v',array('@p'=>$property,'@v'=>$value)));
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
            /*
             * the query is related to nodes !!!!
             * which are contents of self::$d8_custom_entity_type Type ...
             */
            $searchedDayHike = \Drupal::entityQuery('node_type')//TODO the Query is related to nodes 
                    ->condition('cle', $this->__data['cle'], '==')
                    ->execute();

            if (count($searchedDayHike) > 0) {
                drush_log(t('DayHike entity: found a DayHike for the cle:@cle; its content is :', array('@cle' => $this->__data['cle'])));
                dlm($searchedDayHike);
            } else {
                drush_log(t('DayHike entity: did not findd a DayHike for the cle:@cle !!!!', array('@cle' => $this->__data['cle'])));
            }
            return $searchedDayHike;
        } else {
            drush_log(t('DayHike entity does not have a valid "cle" value'));
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
        /* see https://api.drupal.org/api/drupal/core!includes!entity.inc/function/entity_load/8.2.x 
         * also in core/includes/entity.inc line 79-85
         * and its use in http://enzolutions.com/articles/2015/12/03/how-to-get-a-list-of-content-types-in-drupal-8/
         */
        //$storage_manager = \Drupal::service('entity.manager')->getStorage('node_type')->load(self::$d8_custom_entity_type);
        $entity_manager = \Drupal::entityManager();
        $node_manager = $entity_manager->getStorage('node_type');
        $entitites_manager = $node_manager->load(self::$d8_custom_entity_type);

        $new_dayhike_values = array();
        //drush_log(t(' -- from DayHike Entity ...'));
        ///dlm($this);
        foreach (self::$d8_csv_mapping as $map_entry) {
            $depth = count($map_entry['attribute']);
            //drush_log(t('depth is @c',array('@c' => $depth)));
            if ($depth == 1) {
                $attribute_to_get = $map_entry['attribute'][0];
                $value = $this->$attribute_to_get;
                //drush_log(t(' - getting info from @attr, we got: @val',array('@attr' => $attribute_to_get, '@val'=> $value)));
                $new_dayhike_values[$map_entry['field']] = $value;
            } else if ($depth == 2) {
                $object_to_set = $map_entry['attribute'][0];
                $attribute_to_get = $map_entry['attribute'][1];
                $value = $this->$object_to_set->$attribute_to_get;
                //drush_log(t(' - getting info from @obj->@attr, we got: @val',array('@obj' => $object_to_set, '@attr' => $attribute_to_get, '@val'=> $value)));
                $new_dayhike_values[$map_entry['field']] = $value;
            }
        }
        //Drupal does not allow a node's title to be empty (think of an article/page)!!!
        if (!$new_dayhike_values['title']) {
            $new_dayhike_values['title'] = 'xxxxxxxxxxxxxxxx';
        }
        
        if ($nid) {
            $new_dayhike_values['nid'] = $nid;
            drush_log(t(' DayHike Entity: the existing array of values to update  in Drupal8 for the @ct Node/Content Type is:', array('@ct' => self::$d8_custom_entity_type)));
            dlm($new_dayhike_values);
            $node = $entitites_manager->save($new_dayhike_values);
        } else {
            /*
             * We create a new Node see 
             * http://stackoverflow.com/questions/24172791/how-to-programmatically-create-a-node-in-drupal-8
             * see as counselled the devel module !!!
             */
            $new_dayhike_values['type'] = self::$d8_custom_entity_type;
            drush_log(t(' DayHike Entity: Insertion of new values for the @ct Node/Content Type is:', array('@ct' => self::$d8_custom_entity_type)));
            dlm($new_dayhike_values);
            
            $node = Node::create($new_dayhike_values);
            drush_log("+++++ just before inserting ....");
            dlm($new_dayhike_values);
            $node->save();
            drush_log("+++++ just after inserting ....");
        }
        $insertedOrUpdated['d8Entity'] = $new_dayhike_values;
        return $insertedOrUpdated;
    }

}
