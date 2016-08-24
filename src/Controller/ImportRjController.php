<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Controller\ImportController.
 */

namespace Drupal\rif_imports\Controller;

use Drupal\rif_imports\Controller\ImportControllerBase;
use Drupal\rif_imports\Entity\DayHike;

/**
 * Returns responses for devel module routes.
 */
class ImportRjController extends ImportControllerBase {

    protected $mappingImport;
    protected $randoJour;

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
    public function insertOrUpdateDayHikes($path_file) {
        $dayHikes = array();
        if (($handle = $this->openfile($path_file)) !== FALSE) { //TODO à remplacer par une 
            $this->mappingImport = DayHike::$d8_csv_mapping;
            $nodes_inserted = [];
            $nodes_updated = [];
            $treated = 0;
            $row = 0;
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($row > 0) { //the first line is titles'line !!!
                    dlm($data);
                    $num = count($data);
                    drush_log(t('- @num champs à la ligne @row: ', array('@num' => $num, '@row' => $row)));
                    $myDayHike = new DayHike();
                    for ($c = 0; $c < $num; $c++) {
                        foreach ($this->mappingImport as $csv_map) {
                            //dlm($this->mappingImport);
                            ///drush_log(t('getting csvmap'));
                            //dlm($csv_map);
                            if ($csv_map['csv_pos'] == $c) {
                                ///drush_log(t('++ on va entrer pour la position @c la valeurvaleur @data: ', array('@c' => $c, '@data' => $data[$c])));
                                if (count($csv_map['attribute']) == 1) {
                                    $attribute_to_set = $csv_map['attribute'][0];
                                    $myDayHike->$attribute_to_set = $data[$c];
                                } else if (count($csv_map['attribute']) == 2) {
                                    $object_to_set = $csv_map['attribute'][0];
                                    $attribute_to_set = $csv_map['attribute'][1];
                                    $myDayHike->$object_to_set->$attribute_to_set = $data[$c];
                                }
                            }
                        }
                    }
                    //drush_log(t('++ ligne @treated avec succes: ', array('@treated' => $treated)));
                    $dayHikes[] = $myDayHike;
                    $d8DayHikeNids = $myDayHike->d8Exists();
                    drush_log(t('- RjController DayHike Found  for @c (see dlm):',array('@c' => $myDayHike->cle)));
                    dlm($d8DayHikeNids);
                    drush_log(t('- RjController end of DayHike dlm')); 
                    $finalD8DayHike = $myDayHike->d8InsertOrUpdate($d8DayHikeNids);
                    //dlm($finalD8DayHike);
                    if ($finalD8DayHike['nid']) {
                        $nodes_updated[] = $finalD8DayHike['d8Entity'];
                    } else {
                        $nodes_inserted[] = $finalD8DayHike['d8Entity'];
                    }
                    $treated ++;
                }
                $row++;
            }
            fclose($handle);
            //drush_log(t('There were @nombre randonnées de journée successfully imported!', array('@nombre' => $imported)), $type = 'ok');
            //dlm($dayHikes);
            return array('collected_dayhikes' => $dayHikes, 'new_d8_entities' => $nodes_inserted, 'updated_d8_entities' => $nodes_updated);
        }
        return false;
    }

    public function deleteDayHikes($path_file) {
        $dayHikes = array();
        if (($handle = $this->openfile($path_file)) !== FALSE) { //TODO à remplacer par une 
            $this->mappingImport = DayHike::$d8_csv_mapping;
            $nodes_inserted = [];
            $nodes_updated = [];
            $treated = 0;
            $row = 0;
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($row > 0) { //the first line is titles'line !!!
                    dlm($data);
                    $num = count($data);
                    drush_log(t('- @num champs à la ligne @row: ', array('@num' => $num, '@row' => $row)));
                    $myDayHike = new DayHike();
                    for ($c = 0; $c < $num; $c++) {
                        foreach ($this->mappingImport as $csv_map) {
                            //dlm($this->mappingImport);
                            ///drush_log(t('getting csvmap'));
                            //dlm($csv_map);
                            if ($csv_map['csv_pos'] == $c) {
                                ///drush_log(t('++ on va entrer pour la position @c la valeurvaleur @data: ', array('@c' => $c, '@data' => $data[$c])));
                                if (count($csv_map['attribute']) == 1) {
                                    $attribute_to_set = $csv_map['attribute'][0];
                                    $myDayHike->$attribute_to_set = $data[$c];
                                } else if (count($csv_map['attribute']) == 2) {
                                    $object_to_set = $csv_map['attribute'][0];
                                    $attribute_to_set = $csv_map['attribute'][1];
                                    $myDayHike->$object_to_set->$attribute_to_set = $data[$c];
                                }
                            }
                        }
                    }
                    //drush_log(t('++ ligne @treated avec succes: ', array('@treated' => $treated)));
                    $dayHikes[] = $myDayHike;
                    $d8DayHikeNids = $myDayHike->d8Exists();
                    drush_log(t('- RjController DayHike Found  for @c (see dlm):',array('@c' => $myDayHike->cle)));
                    dlm($d8DayHikeNids);
                    drush_log(t('- RjController end of DayHike dlm')); 
                    $myDayHike->d8Delete($d8DayHikeNids);
                    $treated ++;
                }
                $row++;
            }
            fclose($handle);
            //drush_log(t('There were @nombre randonnées de journée successfully imported!', array('@nombre' => $imported)), $type = 'ok');
            //dlm($dayHikes);
            return array('to_remove' => $row, 'removed' => $treated);
        }
        return false;
    }

}
