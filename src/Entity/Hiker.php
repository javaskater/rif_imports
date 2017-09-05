<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Entity\ImportController.
 */
namespace Drupal\rif_imports\Entity;

use Drupal\node\Entity\Node;
use Drupal\rif_imports\Entity\DrupalHiker;

class Hiker {

    /*
     * D8's machine name custom entity type !!!
     */
    public static $d8_custom_entity_type = 'adherent';

    /*
     * field is the machine name ot the field int the D8's custom entity type
     * csv_pos is the column number (starting with 0) int the CSV file's line where the corresponding information is to be found
     * attribute: is the attribute name in this object.... If the arrays length is 2,
     * - the first name is the name of the TrainRide Object (see constructor)
     * - the second name is the name of the attribute of the preceding TrainRide Object
     */
    public static $d8_csv_animateur_mapping = array(
        array(
            'field' => 'field_cle_adherent',
            'csv_pos' => O,
            'attribute' => array(
                'entity' => 'user',
                'attr' => 'username'
            )
        ),
        array(
            'field' => 'title',
            'csv_pos' => 1,
            'attribute' => array(
                'attr' => 'surnom'
            )
        )
    );

    public static $d8_csv_adherent_mapping = array(
        array(
            'field' => 'field_login',
            'csv_pos' => 0,
            'attribute' => array(
                'entity' => 'user',
                'attr' => 'username'
            )
        ),
        array(
            'field' => 'field_mail_adherent',
            'csv_pos' => 12,
            'attribute' => array(
                'entity' => 'user',
                'attr' => 'email'
            )
        ),
        array(
            'field' => 'field_nom_adherent',
            'csv_pos' => 2,
            'attribute' => array(
                'attr' => 'nom'
            )
        ),
        array(
            'field' => 'field_prenom_adherent',
            'csv_pos' => 3,
            'attribute' => array(
                'role' => 'prenom'
            )
        ),     
        array(
            'field' => 'field_code_postal_adherent',
            'csv_pos' => 6,
            'attribute' => array(
                'attr' => 'codePostal'
            )
        )
    );

    /*
     * managing dynamic attributes through custom getters and setters
     * see paragraph 7.11 of PHP In Action
     * OReilly Books ...
     */
    protected $__data = array(
        'user' => false,
        'surnom' => false,
        'nom' => false,
        'prenom' => false,
        'codePostal' => false
    );

    /*
     * Just for the two Entity References
     *
     * ( User and Role )
     *
     * Managing users programmatically
     * https://www.drupal.org/node/2445521
     */
    public function __construct($username = NULL)
    {
        $this->__data['user'] = new DrupalHiker($username);
    }

    /*
     * managing dynamic attributes through custom getters and setters
     * see paragraph 7.11 of PHP In Action
     * OReilly Books ...
     */
    public function __get($property)
    {
        $result = false;
        
        if (isset($this->__data[$property])) {
            $result = $this->__data[$property];
        }
        // drush_log(t('from DayHike Entity, getting @p the value obtained is @v',array('@p'=>$property,'@v'=>$result)));
        return $result;
    }

    /*
     * managing dynamic attributes through custom getters and setters
     * see paragraph 7.11 of PHP In Action
     * OReilly Books ...
     */
    public function __set($property, $value)
    {
        // drush_log(t('from DayHike Entity, setting @p with @v',array('@p'=>$property,'@v'=>$value)));
        $this->__data[$property] = $value;
        if ($property == 'date') {
            $property_to_set = 'jourDeplacement';
            $this->__data['aller']->$property_to_set = $value;
            $this->__data['retour']->$property_to_set = $value;
            // dlm($this->__data['aller']);
            // dlm($this->__data['retour']);
        }
    }

    /*
     * If the Hiker does not already exists (nid==false)
     * then we want to insert it as a new Hiker
     * else if it does exist (nid is an >0 integer)
     * then we update the actual Hiker ..
     * creating entitites, comment 2 at :
     * http://stackoverflow.com/questions/24172791/how-to-programmatically-create-a-node-in-drupal-8
     * entity_create (see core/include/entity.inc) is deprecated in favor
     * of Drupal::entityManager/Storage
     */
    public function d8InsertOrUpdate($nids = false)
    {
        $insertedOrUpdated = array(
            'nid' => false,
            'd8Entity' => false
        );
        /*
         * see https://api.drupal.org/api/drupal/core!includes!entity.inc/function/entity_load/8.2.x
         * also in core/includes/entity.inc line 79-85
         * and its use in http://enzolutions.com/articles/2015/12/03/how-to-get-a-list-of-content-types-in-drupal-8/
         */
        // $storage_manager = \Drupal::service('entity.manager')->getStorage('node_type')->load(self::$d8_custom_entity_type);
        $entity_manager = \Drupal::entityTypeManager();
        $node_manager = $entity_manager->getStorage('node_type');
        //$entitites_manager = $node_manager->load(self::$d8_custom_entity_type);
        
        $new_hikers_values = array();
        // drush_log(t(' -- from DayHike Entity ...'));
        // /dlm($this);
        foreach (self::$d8_csv_mapping as $map_entry) {
            $depth = count($map_entry['attribute']);
            // drush_log(t('depth is @c',array('@c' => $depth)));
            if ($depth == 1) {
                $attribute_to_get = $map_entry['attribute'][0];
                $value = $this->$attribute_to_get;
                // drush_log(t(' - getting info from @attr, we got: @val',array('@attr' => $attribute_to_get, '@val'=> $value)));
                $new_hikers_values[$map_entry['field']] = $value;
            } else if ($depth == 2) {
                $object_to_set = $map_entry['attribute'][0];
                $attribute_to_get = $map_entry['attribute'][1];
                $value = $this->$object_to_set->$attribute_to_get;
                // drush_log(t(' - getting info from @obj->@attr, we got: @val',array('@obj' => $object_to_set, '@attr' => $attribute_to_get, '@val'=> $value)));
                $new_hikers_values[$map_entry['field']] = $value;
            }
        }
        // Drupal does not allow a node's title to be empty (think of an article/page)!!!
        if (! $new_hikers_values['title']) {
            $new_hikers_values['title'] = $new_hikers_values['prenom'].'_'.$new_hikers_values['nom'];
        }
        
        if ($nids && count($nids) == 1) {
            foreach ($nids as $k => $v) {
                $new_hikers_values['nid'] = $v;
            }
            drush_log(t('+++ DayHike Entity: Updating the (existing) Drupal8 node : @nid of : @ct Node/Content Type ....', array(
                '@nid' => $new_hikers_values['nid'],
                '@ct' => self::$d8_custom_entity_type
            )));
            $myHikerD8Node = Node::load($new_hikers_values['nid']);
            $myHikerD8Node->revision = 1; // important pour créer une nouvelle révision Ne fonctionne pas alors que fonctionne en manuel !!!!!
            $myHikerD8Node->setNewRevision();
            dlm($new_hikers_values);
            
            $myHikerD8Node->set('title', $new_hikers_values['title'], FALSE);
            
            $myHikerD8Node->body->value = $new_hikers_values['body'];
            
            // $myHikerD8Node->body->format = 'full_html';
            // $myHikerD8Node->log = t('Il s\'agit de la révision qui prend effet au %date', array('%date' => $donnees_drupal['field_begin_date']));
            
            $myHikerD8Node->set('field_cle', $new_hikers_values['field_cle'], FALSE);
            
            $myHikerD8Node->set('field_type', $new_hikers_values['field_type'], FALSE);
            
            $myHikerD8Node->set('field_date', $new_hikers_values['field_date'], FALSE);
            
            $myHikerD8Node->set('field_gare_depart_aller', $new_hikers_values['field_gare_depart_aller'], FALSE);
            $myHikerD8Node->set('field_heure_depart_aller', $new_hikers_values['field_heure_depart_aller'], FALSE);
            $myHikerD8Node->set('field_gare_arrivee_aller', $new_hikers_values['field_gare_arrivee_aller'], FALSE);
            $myHikerD8Node->set('field_heure_arrivee_aller', $new_hikers_values['field_heure_arrivee_aller'], FALSE);
            
            $myHikerD8Node->set('field_gare_depart_retour', $new_hikers_values['field_gare_depart_retour'], FALSE);
            $myHikerD8Node->set('field_heure_depart_retour', $new_hikers_values['field_heure_depart_retour'], FALSE);
            $myHikerD8Node->set('field_gare_arrivee_retour', $new_hikers_values['field_gare_arrivee_retour'], FALSE);
            $myHikerD8Node->set('field_heure_arrivee_retour', $new_hikers_values['field_heure_arrivee_retour'], FALSE);
            
            $myHikerD8Node->save();
            
            $new_hikers_values['vid'] = $myHikerD8Node->vid->value;
            $insertedOrUpdated['content'] = $new_hikers_values;
            $insertedOrUpdated['d8Entity'] = $myHikerD8Node;
            
            drush_log(t('+++ DayHike Entity:  The Drupal8 node : @nid of : @ct Node/Content Type has been updated!! Its new version Id is : @vid', array(
                '@nid' => $insertedOrUpdated['nid'],
                '@vid' => $insertedOrUpdated['vid'],
                '@ct' => self::$d8_custom_entity_type
            )));
            
            return $insertedOrUpdated;
        } else if (! $nids || ($nids && count($nids) == 0)) {
            /*
             * We create a new Node see
             * http://stackoverflow.com/questions/24172791/how-to-programmatically-create-a-node-in-drupal-8
             * see as counselled the devel module !!!
             */
            $new_hikers_values['type'] = self::$d8_custom_entity_type;
            drush_log(t(' DayHike Entity: Insertion of new values for the @ct Node/Content Type is:', array(
                '@ct' => self::$d8_custom_entity_type
            )));
            dlm($new_hikers_values);
            
            $node = Node::create($new_hikers_values);
            drush_log("+++++ just before inserting ....");
            dlm($new_hikers_values);
            $node->save();
            drush_log("+++++ just after inserting ....");
            $insertedOrUpdated['content'] = $new_hikers_values;
            $insertedOrUpdated['d8Entity'] = $node;
            return $insertedOrUpdated;
        } else if ($nids && count($nids) > 1) {
            drush_log(t('!!! there are @c nids for the same dayHike with field_cle : @cle we need first to remove them before inserting again', array(
                '@c' => count($nids),
                '@cle' => $this->cle
            )), $type = 'warning');
            $this->d8Delete($nids);
            $node = Node::create($new_hikers_values);
            drush_log("+++++ just before inserting ....");
            dlm($new_hikers_values);
            $node->save();
            drush_log("+++++ just after inserting ....");
            $insertedOrUpdated['content'] = $new_hikers_values;
            $insertedOrUpdated['d8Entity'] = $node;
            return $insertedOrUpdated;
        } else {
            return false;
        }
    }
}
    