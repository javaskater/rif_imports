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
             * see drush tests at rif_imports/drush/tests/DayHikeQueryTest.php
             * it returns an array of ids
             */
            $dayHikeNids = \Drupal::entityQuery('node')//TODO the Query is related to nodes 
                    ->condition('field_cle', $this->__data['cle'], '=')
                    ->execute();
            drush_log(t('+ for cle @c I got ....', array('@c' => $this->__data['cle'])));
            if (count($dayHikeNids) > 0) {
                drush_log(t('DayHike entity: found a DayHike for the cle:@cle; its content is :', array('@cle' => $this->__data['cle'])));
                dlm($dayHikeNids);
            } else {
                drush_log(t('DayHike entity: did not findd a DayHike for the cle:@cle !!!!', array('@cle' => $this->__data['cle'])));
            }
            return $dayHikeNids;
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

    public function d8InsertOrUpdate($nids = false) {
        $insertedOrUpdated = array('nid' => false, 'd8Entity' => false);
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

        if ($nids && count($nids) == 1) {
            foreach ($nids as $k=>$v){
                $new_dayhike_values['nid'] = $v;
            }
            drush_log(t('+++ DayHike Entity: Updating the (existing) Drupal8 node : @nid of : @ct Node/Content Type ....', array('@nid' =>  $new_dayhike_values['nid'],'@ct' => self::$d8_custom_entity_type)));
            $myDayHikeD8Node = Node::load($new_dayhike_values['nid']);
            $myDayHikeD8Node->revision = 1; //important pour créer une nouvelle révision Ne fonctionne pas alors que fonctionne en manuel !!!!!
            $myDayHikeD8Node->setNewRevision();
            dlm($new_dayhike_values);
            
            $myDayHikeD8Node->set('title',$new_dayhike_values['title'],FALSE);
            
            $myDayHikeD8Node->body->value = $new_dayhike_values['body'];
            
            //$myDayHikeD8Node->body->format = 'full_html';
            //$myDayHikeD8Node->log = t('Il s\'agit de la révision qui prend effet au %date', array('%date' => $donnees_drupal['field_begin_date']));
            
            $myDayHikeD8Node->set('field_cle',$new_dayhike_values['field_cle'],FALSE);
            
            $myDayHikeD8Node->set('field_type',$new_dayhike_values['field_type'],FALSE);
            
            $myDayHikeD8Node->set('field_date',$new_dayhike_values['field_date'],FALSE);
            
            $myDayHikeD8Node->set('field_gare_depart_aller',$new_dayhike_values['field_gare_depart_aller'],FALSE);
            $myDayHikeD8Node->set('field_heure_depart_aller',$new_dayhike_values['field_heure_depart_aller'],FALSE);
            $myDayHikeD8Node->set('field_gare_arrivee_aller',$new_dayhike_values['field_gare_arrivee_aller'],FALSE);
            $myDayHikeD8Node->set('field_heure_arrivee_aller',$new_dayhike_values['field_heure_arrivee_aller'],FALSE);
            
            $myDayHikeD8Node->set('field_gare_depart_retour',$new_dayhike_values['field_gare_depart_retour'],FALSE);
            $myDayHikeD8Node->set('field_heure_depart_retour',$new_dayhike_values['field_heure_depart_retour'],FALSE);
            $myDayHikeD8Node->set('field_gare_arrivee_retour',$new_dayhike_values['field_gare_arrivee_retour'],FALSE);
            $myDayHikeD8Node->set('field_heure_arrivee_retour',$new_dayhike_values['field_heure_arrivee_retour'],FALSE);

            $myDayHikeD8Node->save();
            
            $new_dayhike_values['vid'] = $myDayHikeD8Node->vid->value;
            $insertedOrUpdated['content'] = $new_dayhike_values;
            $insertedOrUpdated['d8Entity'] = $myDayHikeD8Node;
            
            drush_log(t('+++ DayHike Entity:  The Drupal8 node : @nid of : @ct Node/Content Type has been updated!! Its new version Id is : @vid', array('@nid' => $insertedOrUpdated['nid'],'@vid' => $insertedOrUpdated['vid'],'@ct' => self::$d8_custom_entity_type)));
            
            return $insertedOrUpdated;
        } else if (!$nids || ($nids && count($nids) == 0)) {
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
            $insertedOrUpdated['content'] = $new_dayhike_values;
            $insertedOrUpdated['d8Entity'] = $node;
            return $insertedOrUpdated;
        } else if ($nids && count($nids) > 1) {
            drush_log(t('!!! there are @c nids for the same dayHike with field_cle : @cle we need first to remove them before inserting again', array('@c' => count($nids), '@cle' => $this->cle)), $type = 'warning');
            $this->d8Delete($nids);
            $node = Node::create($new_dayhike_values);
            drush_log("+++++ just before inserting ....");
            dlm($new_dayhike_values);
            $node->save();
            drush_log("+++++ just after inserting ....");
            $insertedOrUpdated['content'] = $new_dayhike_values;
            $insertedOrUpdated['d8Entity'] = $node;
            return $insertedOrUpdated;
        } else {
            return false;
        }
    }
    /*
     * Utility function to allow for deleting
     * nodes out of an array of nodes' ids !!!!
     */
    public function d8Delete($nids = false) {
        if ($nids && count($nids) > 0) {
            $controller = \Drupal::entityManager()->getStorage('node');
            drush_log(t('@c nodes to remove for field_cle: @cle .', array('@c' => count($nids),'@cle' => $this->cle)));
            foreach ($nids as $nid) {
                drush_log(t('-- removing node with id : @id', array('@id' => $nid)));
                $dayHikeentity = $controller->load($nid);
                $controller->delete(array($dayHikeentity));
            }
        } else {
            drush_log(t('no  node to remove for field_cle: @cle ???', array('@cle' => $this->cle)), $type = 'warning');
        }
    }
}
    