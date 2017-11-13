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
    public function insertOrUpdateHikers($path_file) {
        $hikers = array();
        if (($handle = $this->openfile($path_file)) !== FALSE) { //TODO à remplacer par une 
            $this->mappingImport = DrupalHiker::$d8_csv_adherent_mapping;
            $nodes_inserted = [];
            $nodes_updated = [];
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
                                    $attribute_to_set = $csv_map['attribute']['attr'];
                                    $myHiker->$attribute_to_set = $data[$c];
                                } else if (count($csv_map['attribute']) == 2) {
                                    $object_to_set = $csv_map['attribute']['entity'];
                                    $attribute_to_set = $csv_map['attribute']['attr'];
                                    $myHiker->$object_to_set->$attribute_to_set = $data[$c];
                                }
                            }
                        }
                    }
                    //drush_log(t('++ ligne @treated avec succes: ', array('@treated' => $treated)));
                    $hikers[] = $myHiker;
                    drush_log(t('- RjController end of Hiker dlm')); 
                    $finalD8Hiker = $myHiker->d8InsertOrUpdateHikerNode();
                    /*$finalD8Hiker = $myHiker->returnD8Node()->returnD8User();
                    dlm($finalD8Hiker->getUsername());
                    dlm($finalD8Hiker->getEmail());*/
                    if ($finalD8Hiker['nid']) {
                        $nodes_updated[] = $finalD8Hiker['d8Entity'];
                    } else {
                        $nodes_inserted[] = $finalD8Hiker['d8Entity'];
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
