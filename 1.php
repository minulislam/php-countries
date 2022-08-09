<?php

require './vendor/autoload.php';

use RapidWeb\Countries\Countries;

function array_group_by(array $array, $key)
    {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key) ) {
            trigger_error('array_group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR);
            return null;
        }

        $func = (!is_string($key) && is_callable($key) ? $key : null);
        $_key = $key;

        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($array as $value) {
            $key = null;

            if (is_callable($func)) {
                $key = call_user_func($func, $value);
            } elseif (is_object($value) && property_exists($value, $_key)) {
                $key = $value->{$_key};
            } elseif (isset($value[$_key])) {
                $key = $value[$_key];
            }

            if ($key === null) {
                continue;
            }

            $grouped[$key][] = $value;
        }

        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();

            foreach ($grouped as $key => $value) {
                $params = array_merge([ $value ], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $params);
            }
        }

        return $grouped;
    }




$countries = new Countries;


$grouped = array_group_by( $countries->all(), "region");


$countryByKey=[];
foreach($grouped as $key => $value){
 foreach($value as $country){
    $countryByKey[strtoupper($key)][]=strtoupper($country->name);
 }


}
var_dump(json_encode($countryByKey));

/*
string(4) "Asia"
string(6) "Europe"
string(6) "Africa"
string(7) "Oceania"
string(8) "Americas"
string(9) "Antarctic"
*/
//$country = $countries->getByName('United Kingdom');
var_dump($countries->getByName('United Kingdom'));
var_dump($countries->getByIsoCode('US'));
//var_dump($country);

foreach($countries->all() as $country) {
  //  var_dump($country->name.' - '.$country->isoCodeAlpha2.' - '.$country->isoCodeAlpha3);
}

 // foreach($countries->all() as $country) {

  // var_dump($country->name.' - '.$country->officialName);
// }
