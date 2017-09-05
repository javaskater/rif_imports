<?php

namespace Drupal\rif_imports\Entity;

use \Drupal\user\Entity\User;

/**
 * Description of a 
 * specific Drupal 8 user linked to the RIF roles ...
 */
class DrupalHiker {
    /*
     * The Drupal User associated with the Adherent named as Hiker ....
     */

    private $d8User;
    
    private $adminUser;
    
    /*
     * Creating the Drupal8 User associated
     * with the RIF Hikers
     * see https://www.drupal.org/node/2445521
     */
    public function __construct($username=NULL) {
        $this->adminUser = User::load(1);
        if ($username){
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
        }
        return $result;
    }
    

    /*
     * Create an active  Drupal8 User
     * see  https://www.drupal.org/node/2445521
     * (answer 
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
            $found_user_id = $found_userids[0];
            $found_user = new self($found_user_id);
        }     
        return $found_user;
    }

}
