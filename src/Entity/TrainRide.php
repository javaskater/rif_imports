<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Drupal\rif_imports\Entity;

/**
 * Description of Deplacement
 * see PHP CookBook (OReilly) paragrap 7.11
 * @author jpmena
 */
class TrainRide {
    /*
     * The trains' stations , Gare is the french word for Train station
     */

    protected $__stations = array('gareDepart' => false, 'gareGangement' => false, 'gareArrivee' => false);


    /*
     * The trains' departure and arrival times 
     * in Drupal8 DateTime format
     */
    protected $__trainTimes = array('jourDeplacement' => false,
        'heureDepart' => false, 'heureChangementArrivee' => false, 'heureChangementDepart' => false, 'heureArrivee' => false);

    /*
     * For the usage of the __set method see PHP CookBook (OReilly) paragrap 7.11
     * In case the variable name starts with heure it means 
     * you have to translate the CSV String value 1999/12/30 08:51:00
     * to a PHP time object pointing to 8h50min 
     * In case the variable name starts with jour it means 
     * you have to translate the CSV String value 2016-06-01
     * to a PHP Date object pointing to 6th of january 2016
     * otherwise it is just the string
     */

    public function __set($property, $value) {
        //drush_log(t('+++ --- jPour le TrainRide, avant les if ... je sette la propriété: @prop', array('@prop' => substr($property, 0, 4))));
        if (isset($this->__trainTimes[$property]) && substr($property, 0, 4) == 'jour') {
            $this->__trainTimes[$property] = $this->translateCsvToTime($value);
            return $this->__trainTimes[$property];
        } else if (isset($this->__trainTimes[$property]) && substr($property, 0, 5) == 'heure') {
            //drush_log(t('+++ --- jPour le TrainRide, je sette la propriété: @prop', array('@prop' => $property)));
            $this->__trainTimes[$property] = $this->translateCsvToTime($value);
            return $this->__trainTimes[$property];
        } else if (isset($this->__stations[$property]) && substr($property, 0, 4) == 'gare') {
            $this->__stations[$property] = $value;
            return $this->__stations[$property];
        } else {
            return false;
        }
    }

    /*
     * For the usage of the __set method 
     * see PHP CookBook (OReilly) paragrap 7.11
     * 
     */

    public function __get($property) {
        if (isset($this->__trainTimes[$property])) {
            return $this->__trainTimes[$property];
        } else if (isset($this->__stations[$property])) {
            return $this->__stations[$property];
        } else {
            return false;
        }
    }

    /*
     * translating csv String value 1999/12/30 08:51:00
     * to a D8 DateTime object pointing to 2016-06-01T08:51:00
     * (if the 6th of january 2016 is the day of the ride)
     * but also
     * translating csv String value 2016-06-01 to 2016-06-01
     * and translating all other kind of value to false (no pattern matched)
     * 
     */

    function translateCsvToTime($date_time_csv) {
        $PATTERN_DATE_CSV = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
        if (preg_match($PATTERN_DATE_CSV, $date_time_csv, $matches)) {
            return $date_time_csv;
        } else {
            $PATTERN_DATETIME_CSV = '/^([0-9]{4}\/[0-9]{2}\/[0-9]{2})\s+([0-9]{2}:[0-9]{2}:[0-9]{2})$/';
            if (preg_match($PATTERN_DATETIME_CSV, $date_time_csv, $matches)) {
                $ride_day = ($this->__trainTimes['jourDeplacement'] != false) ? $this->__trainTimes['jourDeplacement'] : date('Y-m-d');
                return $ride_day . 'T' . $matches[2];
            } else {
                return false;
            }
        }
    }

}
