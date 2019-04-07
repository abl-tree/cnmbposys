<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 07/04/2019
 * Time: 10:22 AM
 */

namespace App\Services;


class ExcelDateService
{
    /**
     * Converts excel time to readable PHP / SQL Datetime
     * @param $dateValue
     * @return string | datetime
     */
    public function excelDateToPHPDate ($dateValue ){

        if (!is_numeric($dateValue)) {
            if (!$this->checkIsAValidDate($dateValue)) {
                return $dateValue;
            }
            else {
                return date('m-d-Y h:i:s', strtotime($dateValue));
            }
        }

        else{
            $unix_date = ($dateValue - 25569) * 86400;
            $dateValue = 25569 + ($unix_date / 86400);
            $unix_date = ($dateValue - 25569) * 86400;
            return gmdate("d-m-Y H:i:s", $unix_date);
        }

    }

    /**
     * Checks if the passed parameter is a valid date
     * @param $myDateString
     * @return bool
     */
    public function checkIsAValidDate($myDateString){
        return (bool)strtotime($myDateString);
    }
}