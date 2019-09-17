<?php

require "countryDataAccess.php";
require "countryDataControl.php";

if(isset( $_GET['elemId'] )) {

     $data['elemId'] = urldecode($_GET['elemId']);
     $data[$data['elemId']] = urldecode($_GET[$data['elemId']]);


     $data['searchValue'] = str_replace(" ","+",$data[$data['elemId']]);
     $ctryData = new countryDataControl($data);

     if($data['elemId'] == 'cName') {
       $result = $ctryData->FindCountryByName();
     } 

     if($data['elemId'] == 'cCode') {
       $result = $ctryData->findCountryByCountryCode();
     } 

     if($data['elemId'] == 'cCity') {
       $result = $ctryData->findCountryByCapital();
     }

     if($data['elemId'] == 'cuCode') {
       $result = $ctryData->findCountryByCurrency();
     }

     if($data['elemId'] == 'Lang') {
     	echo "in here \n";
       $result = $ctryData->findCountryByLanguage();
     }

     echo $result;
}
?>