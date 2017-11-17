<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Controller\ImportController.
 */

namespace Drupal\rif_imports\Controller;

use Drupal\rif_imports\Controller\ImportControllerBase;
use Drupal\rif_imports\Entity\DrupalHiker;

/**
 * Returns responses for devel module routes.
 */
class ImportAdhController extends ImportControllerBase {

    protected $mappingImport;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get nids of the nodes to delete.
     *
     * @param array $roles
     *   Array of roles.
     *
     * @return array
     *   Array of nids of nodes to delete.
     */
    public function insertOrUpdateHikers($path_file, $roles=[]) {
        $hikers = array();
        if (($handle = $this->openfile($path_file)) !== FALSE) { //TODO à remplacer par une 
            $fic_name = basename ( $path_file, ".csv" );
            $main_role = substr($fic_name, 0, -1);
            $roles = [];
            switch ($main_role){
                case 'adherent':
                    $this->mappingImport = DrupalHiker::$d8_csv_adherent_mapping;
                    $roles[] = 'adherent';
                    break;
                case 'animateur':
                    $this->mappingImport = DrupalHiker::$d8_csv_animateur_mapping;
                    $roles[] = 'adherent';
                    $roles[] = 'animateur';
                    break;
            }
            $nodes_inserted = [];
            $nodes_updated = [];
            $treated = 0;
            $row = 0;
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($row > 0) { //the first line is titles'line !!!
                    dlm($data);
                    $num = count($data);
                    //drush_log(t('- @num champs à la ligne @row: ', array('@num' => $num, '@row' => $row)));
                    $myHiker = new DrupalHiker($roles);
                    for ($c = 0; $c < $num; $c++) {
                        foreach ($this->mappingImport as $csv_map) {
                            //dlm($this->mappingImport);
                            ///drush_log(t('getting csvmap'));
                            //dlm($csv_map);
                            if ($csv_map['csv_pos'] == $c) {
                                $key = $csv_map['attribute'];
                                $val = $data[$c];
                                //drush_log(t('++ pour attribut @att on va entrer pour la position @c la valeurvaleur @data: ', array('@att' => $key, '@c' => $c, '@data' => $val)));
                                $attribute_to_set = $csv_map['attribute'];
                                $myHiker->$attribute_to_set = $val;
                            }
                        }
                    }
                    //drush_log(t('++ ligne @treated avec succes: ', array('@treated' => $treated)));
                    $hikers[] = $myHiker;
                    //drush_log(t('- RjController end of Hiker dlm')); 
                    $returnD8Action = $myHiker->activateD8USer();
                    /*$finalD8Hiker = $myHiker->returnD8Node()->returnD8User();
                    dlm($finalD8Hiker->getUsername());
                    dlm($finalD8Hiker->getEmail());*/
                    if ($returnD8Action == SAVED_UPDATED) {
                        $nodes_updated[] = $myHiker;
                        drush_log(t('+Updated- we updated the hiker whose name is @name  ', array('@name' => $myHiker->username)));
                    } else if ($returnD8Action == SAVED_NEW) {
                        drush_log(t('+New+ we created the hiker whose name is @name  ', array('@name' => $myHiker->username)));
                        $nodes_inserted[] = $myHiker;
                    }
                    $treated ++;
                }
                $row++;
            }
            fclose($handle);
            //drush_log(t('There were @nombre randonnées de journée successfully imported!', array('@nombre' => $imported)), $type = 'ok');
            //dlm($hikers);
            return array('collected_dayhikes' => $hikers, 'new_d8_entities' => $nodes_inserted, 'updated_d8_entities' => $nodes_updated);
        }
        return false;
    }

    public function deleteHikers($path_file) {
        if (($handle = $this->openfile($path_file)) !== FALSE) { //TODO à remplacer par une 
            $this->mappingImport = DrupalHiker::$d8_csv_adherent_mapping;
            $treated = 0;
            $row = 0;
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($row > 0) { //the first line is titles'line !!!
                    dlm($data);
                    $num = count($data);
                    drush_log(t('- @num champs à la ligne @row: ', array('@num' => $num, '@row' => $row)));
                    $myHiker = new DrupalHiker();
                    for ($c = 0; $c < $num; $c++) {
                        foreach ($this->mappingImport as $csv_map) {
                            //dlm($this->mappingImport);
                            ///drush_log(t('getting csvmap'));
                            //dlm($csv_map);
                            if ($csv_map['csv_pos'] == $c) {
                                ///drush_log(t('++ on va entrer pour la position @c la valeurvaleur @data: ', array('@c' => $c, '@data' => $data[$c])));
                                if (count($csv_map['attribute']) == 1) {
                                    $attribute_to_set = $csv_map['attribute'][0];
                                    $myHiker->$attribute_to_set = $data[$c];
                                } else if (count($csv_map['attribute']) == 2) {
                                    $object_to_set = $csv_map['attribute'][0];
                                    $attribute_to_set = $csv_map['attribute'][1];
                                    $myHiker->$object_to_set->$attribute_to_set = $data[$c];
                                }
                            }
                        }
                    }
                    //drush_log(t('++ ligne @treated avec succes: ', array('@treated' => $treated)));
                    $hikers[] = $myHiker;
                    $d8HikerNids = $myHiker->d8Exists();
                    drush_log(t('- RjController DayHike Found  for @c (see dlm):',array('@c' => $myHiker->cle)));
                    dlm($d8HikerNids);
                    drush_log(t('- RjController end of DayHike dlm')); 
                    $myHiker->d8Delete($d8HikerNids);
                    $treated ++;
                }
                $row++;
            }
            fclose($handle);
            //drush_log(t('There were @nombre randonnées de journée successfully imported!', array('@nombre' => $imported)), $type = 'ok');
            //dlm($hikers);
            return array('to_remove' => $row, 'removed' => $treated);
        }
        return false;
    }

}
