<?php

namespace Drupal\rif_imports\Entity;

use \Drupal\user\Entity\User;

/**
 * Description of a 
 * specific Drupal 8 user linked to the RIF roles ...
 * It is just a wrapper around a standard Drupal8 Userr
 * 
 */
class DrupalHiker {
    
    
    /*
     * field is the machine name ot the field int the D8's custom entity type
     * csv_pos is the column number (starting with 0) int the CSV file's line where the corresponding information is to be found
     * attribute: is the attribute name in this object.... If the arrays length is 2,
     * - the first name is the name of the TrainRide Object (see constructor)
     * - the second name is the name of the attribute of the preceding TrainRide Object
     */
    public static $d8_csv_animateur_mapping = array(
        array(
            'csv_pos' => O,
            'attribute' => 'username' //is it name or username ?
        ),
        array(
            'attribute' => 'field_rif_hiker_nickname',
            'csv_pos' => 1
        )
    );
    
    public static $d8_csv_adherent_mapping = array(
        array(
            'csv_pos' => 0,
            'attribute' => 'username' //is it name or username ?
        ),
        array(
            'csv_pos' => 12,
            'attribute' => 'email'
        ),
        array(
            'attribute' => 'field_rif_hiker_name',
            'csv_pos' => 2
        ),
        array(
            'attribute' => 'field_rif_hiker_firstname',
            'csv_pos' => 3
        ),
        array(
            'attribute' => 'field_rif_hiker_zipcode',
            'csv_pos' => 6
        )
    );
    
    
    /*
     * The Drupal User associated with the Adherent named as DrupalHiker ....
     */
    private $d8User;
    
    private $adminUser;
    
    /*
     * Creating the Drupal8 User associated
     * with the RIF Hikers
     * see https://www.drupal.org/node/2445521
     */
    public function __construct($uid=NULL) {
        $this->adminUser = User::load(1);
        if ($uid){
            $this->d8User = User::load($uid);
        } else {
            $this->d8User = User::create();
        }
    }

    public function __set($property, $value) {
        $this->__conn_attrs[$property] = $value;
        /* see https://www.drupal.org/node/2445521
         * and go to web/core/modules/user/src/Entity/User.php
         */
        switch ($property) {
            case 'username':
                $this->d8User->setUsername($value);
                break;
            case 'password':
                $this->d8User->setPassword($value);
                break;
            case 'email':
                $this->d8User->setEmail($value);
                break;
            case 'roles':
                if(is_array($this->roles) && count($this->roles) > 0){
                    foreach ($this->roles as $active_role){
                        $this->d8User->addRole($active_role);
                    }
                }   
                break;
            /* 
             * for the custtom user fields see
             * https://drupal.stackexchange.com/questions/146308/access-user-fields
             */   
            default:{
                $this->d8User->set($property, $value); 
            }
        }
    }

    
    public function __get($property) {
        $result = NULL;
        switch ($property) {
            case 'username':
                $result = $this->d8User->getUsername();
                break;
            case 'password':
                $result = $this->d8User->getPassword();
                break;
            case 'email':
                $result = $this->d8User->getEmail();
                break;
            case 'roles':
                $attached_roles = $this->d8User->getRoles();
                $result = $attached_roles;
                break;
            /*
             * for the custtom user fields see
             * https://drupal.stackexchange.com/questions/146308/access-user-fields
             */
            default:{
                $result = $this->d8User->get($property);
            }
        }
        return $result;
    }
    

    /*
     * Create an active  Drupal8 User
     * see  https://www.drupal.org/node/2445521
     * (answer )
     * And save it a the same time ...
     */
    public function activateD8USer($language="fr"){
        /*for the first tests password = usernam
         * Later we will ask for sending a connection
         * link !!!
         */ 
        if (!$this->d8User->getPassword()){
            $this->d8User->setPassword($this->d8User->getUsername());
        }
        /*
         * If no email entered then take the admin email !!!
         */
        if (!$this->d8User->getEmail()){
            $this->d8User->setEmail($this->adminUser->getEmail());
        }
        $this->d8User->set("preferred_langcode", $language);
        $this->d8User->set("preferred_admin_langcode", $language);
        $this->d8User->activate();
        return $this->d8User->save();
    }

    public function returnD8USer(){
        return $this->d8User;
    }
    
    public static function findByUserName($username){
        /*
         * TODO test if the Hikers is already in the database and returns it if the case ...
         * Managing users programmatically
         * https://www.drupal.org/node/2445521
         * also https://lakshminp.com/using-entity-api-drupal-8
         */
        $found_user = NULL;
        $query = \Drupal::entityQuery('user')->condition('name', $username, 'CONTAINS');
        $found_userids = $query->execute();
        if(count($found_userids) == 1){
            $cle_user_id = key($found_userids);
            $user_id = (int)$found_userids[$cle_user_id];
            $found_user = new self($user_id);
        }     
        return $found_user;
    }

}
