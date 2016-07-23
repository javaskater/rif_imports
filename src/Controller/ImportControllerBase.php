<?php

/**
 * @file
 * Contains \Drupal\rif_imports\Controller\ImportControllerBase.
 */

namespace Drupal\rif_imports\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns Database connection ...
 */
abstract class ImportControllerBase extends ControllerBase {

    protected $connection;

    public function __construct() {
        $this->connection = \Drupal::database();
    }
    
    /*
     * Make that function protected and the work in the Contorller 
    */
    public function openfile($path_file,$extension='csv') {
        if ($path_file) {
            if (is_file($path_file)) {
                $info = pathinfo($path_file);
                dlm($info);
                if ($info["extension"] == $extension) {
                    return $handle = fopen($path_file, "r");
                } else {
                    drush_log(t("the file @data_file has not the .csv extension!!!", array('@data_file' => $path_file)), 'error');
                    return false;
                }
            } else {
                drush_log(t("the file @data_file does not exist or is not a regular file!!!", array('@data_file' => $path_file)), 'error');
                return false;
            }
        } else {
            drush_log(t("no file to import specified"), 'error');
            return false;
        }
    }

}
