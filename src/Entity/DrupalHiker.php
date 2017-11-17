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
     * managing dynamic attributes through custom getters and setters
     * see paragraph 7.11 of PHP In Action
     * OReilly Books ...
     */
    protected $__data = array('username' => false, 'email' => false, 'field_rif_hiker_name' => false, 'field_rif_hiker_firstname' => false,
        'field_rif_hiker_zipcode' => false, 'field_rif_hiker_nickname' => false, 'roles' => []);
    
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
    public function __construct($roles = []) {
        $this->adminUser = User::load(1);
        $this->d8User = NULL;
        $this->roles = $roles;
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
        switch ($property) {
            case 'username':
                $founduser = self::findByUserName($value);
                if($founduser){
                    $this->d8User = $founduser;
                } else {
                    $this->d8User = User::create();
                }
                if(is_array($this->roles) && count($this->roles) > 0){
                    foreach ($this->roles as $active_role){
                        $this->d8User->addRole($active_role);
                    }
                }   
                break;
        }
        $this->__data[$property] = $value;
    }

    private function d8set($property, $value) {
        /* see https://www.drupal.org/node/2445521
         * and go to web/core/modules/user/src/Entity/User.php
         */
        if($value){ // in case of updatuing adherent values with animateur csv values...
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
                /* 
                 * for the custtom user fields see
                 * https://drupal.stackexchange.com/questions/146308/access-user-fields
                 */   
                default:{
                    $this->d8User->set($property, $value); 
                }
            }
        }
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
        foreach ($this->__data as $property => $value){
            $this->d8set($property, $value);
        }
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
            $found_user = User::load($user_id);
        }     
        return $found_user;
    }

}
