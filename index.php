<?php
require_once '../func_db/mydb.php'; //db読み込み
require_once '../func_db/function.php'; //function読み込み

$request_method = get_request_method();
$limits = [
    5 => '5',
    15 => '15',
    30 => '30',
];
    
session_start();


if(isset($_SESSION['id'])==false){
    setcookie('id', '9999', time() + 60 * 60 * 24 * 365);
}else{
    setcookie('id', '', time()-42000); //$_SESSION['id']があるのでこれは不要のため削除
}

if($request_method==='GET'){
    
    try{ //get送受信につきURLからパラメーターをいじられたら嫌なのでそれを防ぐ
        if(isset($_GET['page']) && $_GET['page']!==''){
            if(preg_match('/^[1-9][0-9]*$/', $_GET['page'])===false){
                throw new Exception('Recevied invalid parameters.');
            }else{
                $page = (int)$_GET['page'];
            }
        }
    } catch (Exception $ex) {
        $errMsg[] = $ex->getMessage();
    }
    
    try{ //get送受信につきURLからパラメーターをいじられたら嫌なのでそれを防ぐ
        if(isset($_GET['limit']) && $_GET['limit']!==''){
            if(!array_key_exists($_GET['limit'], $limits)){
                throw new Exception('Recevied invalid parameters.');
            }else{
    //    // select * from comments limit OFFSET, COUNT
    //    // page offset count 5件ずつの場合
    //    // 1    0      5
    //    // 2    5      5
    //    // 3    10     5
            $_SESSION['limit'] = $_GET['limit']; //session変数に代入
            }
        }
    } catch (Exception $ex) {
        $errMsg[] = $ex->getMessage();
    }
}

if (isset($_GET['page']) && preg_match('/^[1-9][0-9]*$/', $_GET['page'])) {
    $page = (int)$_GET['page'];
} else {
    $page = 1;
}

if(isset($_SESSION['limit'])){
    $lines_per_page = (int)$_SESSION['limit'];
}else{
//    $tmp = array_keys($limits);//なかったら$limitsの最初のキー「5」をセット
//    $lines_per_page = (int)array_shift($tmp);//なかったら$limitsの最初のキー「5」をセット
    $lines_per_page = 30;
}

$offset = $lines_per_page * ($page - 1);

//アイテム一覧取得
$sql = "
    SELECT ayikb_item_table.item_id, ayikb_item_table.item_name, ayikb_item_table.price, 
    ayikb_item_table.qty, ayikb_item_table.status, 
    ayikb_img_table.img_ext, ayikb_img_table.img_raw 
    FROM ayikb_item_table 
    JOIN ayikb_img_table 
    ON ayikb_item_table.item_id = ayikb_img_table.item_id 
    WHERE ayikb_item_table.status = 1
    ORDER BY ayikb_item_table.item_id desc 
    LIMIT ".$offset.", ".$lines_per_page;
$stmh = $pdo->query($sql);
while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
    $item[]= h_arr($row);
}

//アイテム件数取得
$sql = "SELECT COUNT(*) FROM ayikb_item_table WHERE status = 1;";
$stmh = $pdo->query($sql);
$total_lines = $stmh->fetchColumn();
$total_pages = ceil($total_lines / $lines_per_page); //ceilで切り上げ

include_once './index_view.php'; 