<?php

//use \Drupal\user\Entity\User;
/*
 * To test it call:
 * jpmena@jpmena-HP-ProDesk-600-G2-MT ~/RIF/d8devextranet/web $ drush scr modules/custom/rif_imports/tests/userQuery.php
 
$username="admin";
$query = \Drupal::entityQuery('user')->condition('name', $username, 'CONTAINS');
$found_uids = $query->execute();
foreach($found_uids as $user_id){
    echo "+ found user : ".$user_id."\n";
    $the_user = User::load($user_id);
    var_dump($the_user);
    
}
*/

use Drupal\rif_imports\Entity\DrupalHiker;

/*
 * To test it call:
 * jpmena@jpmena-HP-ProDesk-600-G2-MT ~/RIF/d8devextranet/web $ drush scr modules/custom/rif_imports/tests/userQuery.php
 */
 
$username="admin";

$found_hiker=DrupalHiker::findByUserName($username);

var_dump($found_hiker->roles);