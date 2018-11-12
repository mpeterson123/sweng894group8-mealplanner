<?php
namespace Base\Helpers;
require_once __DIR__.'/../../vendor/autoload.php';

class Format{

    public static function validatorErrors($errors){
        $errorHTML ='<p>There are errors in your form.</p><ul>';
        foreach ($errors as $fieldName => $errorMessages) {
            foreach ($errorMessages as $errorMessage) {
                $errorHTML .= "<li>${errorMessage}</li>";
            }
        }
        $errorHTML .='</ul></p>';
        return $errorHTML;
    }

    public static function date($date) {
        $dateArray = explode('/', $date);
        $month = $dateArray[0];
        $day = $dateArray[1];
        $year = $dateArray[2];

        return $year.'-'.$month.'-'.$day;
    }
}
