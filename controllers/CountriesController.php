<?php

namespace app\controllers;

use app\models\Countries;

class CountriesController extends \yii\rest\ActiveController{

public $modelClass = 'app\models\Countries';


public function actionIndex(){
        $get = json_decode(stripslashes($_POST['req']));
        // Get data from object
        $name = $get->name; // Get name you send
        $age = $get->age; // Get age of user
        print_r($name);
        print_r($age);
//        $connection = Yii::$app->db;
//                $setSql = "INSERT INTO countries values (NULL, '" . $name . "') ";
//                $command= $connection->createCommand($setSql);
//                $command->execute();  
}

public function actionView($id){
 

}

public function actionCreate(){

}

public function actionUpdate(){

}

public function actionDelete(){

}

public function actionOptions(){

}

}
