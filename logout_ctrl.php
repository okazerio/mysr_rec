<?php

require_once '../func_db/mydb.php'; //db読み込み
require_once '../func_db/function.php'; //function読み込み

$request_method = get_request_method();

session_start();

if(!isset($_SESSION['name'])){
    header('Location: ./login_ctrl.php');
    exit;
}

if($request_method==='POST'){
    
    $_SESSION = []; //session変数をクリア

    if(isset($_COOKIE[session_name()])){
        $params = session_get_cookie_params(); //sessionに関連する設定を取得
    }
    
    session_destroy();
    
    $successMsg[] = 'Log-out is done.';
    
}

include_once './logout_view.php'; 