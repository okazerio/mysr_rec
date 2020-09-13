<?php

session_start();

require_once '../func_db/mydb.php'; //db読み込み
require_once '../func_db/function.php'; //function読み込み



$request_method = get_request_method();
$areas = [
    1262321 => 'Mysore, India',
    4350049 => 'California, US',
    1850147 => 'Tokyo, Japan',
];


if($_SESSION['id']!=='1'){
    header('Location: ./index.php');
    exit;
}

if($request_method==='POST'){
    
}
if($request_method==='GET'){
    try{ //get送受信につきURLからパラメーターをいじられたら嫌なのでそれを防ぐ
        if(isset($_GET['area']) && $_GET['area']!==''){
            if(!array_key_exists($_GET['area'], $areas)){
                throw new Exception('Recevied invalid parameters.');
            }else{
                $_SESSION['area'] = $_GET['area']; //session変数に代入
            }
        }
    } catch (Exception $ex) {
        $errMsg[] = $ex->getMessage();
    }
}

if(isset($_SESSION['area'])){
    $area_id = (int)$_SESSION['area'];
}else{
    $tmp = array_keys($areas);//なかったら$areaの最初のキー「mysore」をセット
    $area_id = (int)array_shift($tmp);//なかったら$areaの最初のキー「mysore」をセット
}

$weather_forcast_response = get_weather_forcast('forecast', $area_id);
$weather_forcast_list = $weather_forcast_response['list'];


include_once './admin_top_view.php'; 