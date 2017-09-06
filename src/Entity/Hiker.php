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
            'field' => 'field_login',
            'csv_pos' => O,
            'attribute' => array(
                'entity' => 'user',
                'attr' => 'username' //is it name or username ?
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
                'attr' => 'username' //is it name or username ?
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
                'attr' => 'prenom'
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
    protected $__data = [
        'user' => ['value' => false, 'field' => 'field_login'],
        'surnom' => ['value' => false, 'field' => 'title'],
        'nom' => ['value' => false, 'field' => 'field_nom_adherent'],
        'prenom' => ['value' => false, 'field' => 'field_prenom_adherent'],
        'codePostal' => ['value' => false, 'field' => 'field_code_postal_adherent']
    ];
    
    protected $node_adherent = NULL;

    /*
     * 
     */
    public function __construct($nid = NULL)
    {
        if ($nid){
            //TODO à terminer à la façon du DrupalHiker
            $this->node_adherent = Node::load($nid);
            foreach ($this->__data as $attribute => $dict_values){
                $field_name = $dict_values['field'];
                $this->__data[$attribute]['value'] =  $this->node_adherent->$field_name;
            }
        }else{
            $this->__data['user']['value'] = new DrupalHiker();
        }
    }

    /*
     * managing dynamic attributes through custom getters and setters
     * see paragraph 7.11 of PHP In Action
     * OReilly Books ...
     */
    public function __get($property)
    {
        $result = false;
        
        switch ($property) {
            case 'email':
            case 'username':
                $result = $this->__data['user']['value']->$property;
                break;
            default:{
                $result = $this->__data[$property]['value'];
            }
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
        switch ($property) {
            case 'email':
            case 'username':
                if (!$this->__data['user'])
                    $this->__data['user'] = new DrupalHiker();
                $this->__data['user']['value']->$property=$value;
                break;
            default:{
                $this->__data[$property]['value'] = $value;
            }
        }
    }
    
    public function returnD8Node(){
        return $this->user;
    }

    
    public static function findHikerNodeByLogin($login)
    {
        // sees https://lakshminp.com/using-entity-api-drupal-8
        /*
         * TODO test if the Hikers is already in the database and returns it if the case ...
         * Managing users programmatically
         * https://www.drupal.org/node/2445521
         * also https://lakshminp.com/using-entity-api-drupal-8
         */
        $found_user = NULL;
        $query = \Drupal::entityQuery('node')->condition('field_login', $login, 'CONTAINS');
        $found_hikers_node_ids = $query->execute();
        if(count($found_hikers_node_ids) == 1){
            $cle_user_id = key($found_hikers_node_ids);
            $user_id = (int)$found_hikers_node_ids[$cle_user_id];
            $found_hiker = new self($found_hikers_node_ids);
        }
        return $found_hiker;
    }
    

    /*
     * Redo it see https://lakshminp.com/using-entity-api-drupal-8
     */
    public function d8InsertOrUpdateHikerNode()
    {
        $hiker = NULL;
        $login = $this->user['value'];
        if ($login){
            $hiker = self::findHikerNodeByLogin($login);
            if(!$hiker){
                // see https://lakshminp.com/using-entity-api-drupal-8
                $args = array();
                foreach ($this->__data as $attribute => $dict_values){
                    $field_name = $dict_values['field'];
                    $args[$field_name] = $this->__data[$attribute]['value'];
                }
                $hiker = Node::create($args); //see https://lakshminp.com/using-entity-api-drupal-8 to check if we need to mke an arrayt out of a user
            }else{ //updates the Existing Node 
                foreach ($this->__data as $attribute => $dict_values){
                    $field_name = $dict_values['field'];
                    $this->node_adherent->$field_name = $this->__data[$attribute]['value'];
                }
            }
            $hiker->save();
        }
    }
}
    