<?php

require_once 'mydb.php'; //db読み込み

ini_set("allow_url_fopen",1);

$date = date('Y/m/d H:i:s');
$pdo = db_connect();

//特殊文字をHTMLエンティティに変換する(変数)
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
////特殊文字をHTMLエンティティに変換する(配列)
function h_arr($assoc_arr){
    foreach($assoc_arr as $key => $val){
        if($key !== 'img_raw' && $key !== 'img_ext'){ //画像情報はノータッチ
            $assoc_arr[$key] = h($val);
        }else{
            $assoc_arr[$key] = $val;
        }
    }
    return $assoc_arr;
}

function get_request_method(){
    return $_SERVER['REQUEST_METHOD'];
}

function get_weather_forcast($api_type, $area_id){
        $api_base = 'http://api.openweathermap.org/data/2.5/';
        $api_parm = '?id='.$area_id.'&units=metric&APPID=';
        $app_id = "d4d7d6452f830e1762c7b852d463409a";
        $api_url = $api_base.$api_type.$api_parm.$app_id;
        
        $ch = curl_init(); 
        curl_setopt( $ch, CURLOPT_URL, $api_url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec( $ch ); 
        curl_close($ch);
        
//        return $weather_responses = json_decode(file_get_contents($api_url), true);
        return $weather_responses = json_decode($result, true);
}
function get_order_num($pdo, $session_id, $order_id){
    $sql = "SELECT order_num FROM ayikb_order_table WHERE id = ".$session_id." AND order_id = ".$order_id;
    $stmh = $pdo->query($sql);
    while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
        $order_num_from_mysql=$row;
    }
    return $order_num_from_mysql['order_num'];
}
function get_qty($pdo, $post_item_id){
    $sql = "SELECT qty FROM ayikb_item_table WHERE item_id = ".$post_item_id;
    $stmh = $pdo->query($sql);
    while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
        $qty=$row;
    }
    return $qty['qty'];
}
function get_status($pdo, $post_item_id){
    $sql = "SELECT status FROM ayikb_item_table WHERE item_id = ".$post_item_id;
    $stmh = $pdo->query($sql);
    while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
        $status=$row;
    }
    return $status['status'];
}

function get_cart_exists($pdo, $session_id){ //特定商品がユーザーのカートに存在するか確認
    try{
        $sql = "SELECT * FROM ayikb_cart_table WHERE id = ".$session_id;
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
            $cart_exists = $row;
        }
        if(isset($cart_exists)){
            return true;  
        }else{
            return false;  
        }
        
    } catch (Exception $ex) {
        return $errMsg = $ex->getMessage();     
    }
}

function get_cart_info_exists($pdo, $session_id, $post_item_id){ //特定商品がユーザーのカートに存在するか確認
    try{
        $sql = "SELECT id, item_id FROM ayikb_cart_table WHERE id = ".$session_id." AND item_id = ".$post_item_id;
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
            $cart_info = $row;
        }
        if(isset($cart_info)){
            return $cart_info;  
        }
        
    } catch (Exception $ex) {
        return $errMsg = $ex->getMessage();     
    }
}

function get_cart_info($pdo, $session_id){ //ユーザーのカートにある商品IDとその数量を取得
    try{
        $sql = "
            SELECT ayikb_cart_table.item_id, ayikb_cart_table.cart_qty, ayikb_item_table.item_name 
            FROM ayikb_cart_table 
            JOIN ayikb_item_table 
            ON ayikb_cart_table.item_id = ayikb_item_table.item_id 
            WHERE ayikb_cart_table.id = ".$session_id;
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
            $cart_info[] = $row;
        }
        if(isset($cart_info)){
            return $cart_info;  
        }
        
    } catch (Exception $ex) {
        $pdo->rollBack();
        return $errMsg = $ex->getMessage();     
    }
}

function get_subtotal_of_price($pdo, $session_id){
    try{
        $sql = "
            SELECT SUM(ROUND(ayikb_cart_table.cart_qty * ayikb_item_table.price)) 
            FROM ayikb_cart_table 
            JOIN ayikb_item_table 
            ON ayikb_cart_table.item_id = ayikb_item_table.item_id 
            WHERE ayikb_cart_table.id = ".$session_id;
            
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_COLUMN)){
            $subtotal = $row;
        }
            return $subtotal;
        
    } catch (Exception $ex) {
        $pdo->rollBack();
        return $errMsg = $ex->getMessage();     
    }
}
function get_subtotal_of_price_on_order($pdo, $session_id, $order_num_from_mysql){
    try{
        $sql = "
            SELECT SUM(ROUND(ayikb_order_table.order_qty * ayikb_item_table.price)) 
            FROM ayikb_order_table 
            JOIN ayikb_item_table 
            ON ayikb_order_table.item_id = ayikb_item_table.item_id 
            WHERE ayikb_order_table.id = ".$session_id." AND ayikb_order_table.order_num = '".$order_num_from_mysql."';";
            
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_COLUMN)){
            $subtotal = $row;
        }
            return $subtotal;
        
    } catch (Exception $ex) {
        $pdo->rollBack();
        return $errMsg = $ex->getMessage();     
    }
}
function get_subtotal_of_tax($pdo, $session_id){
    try{
        $sql = "
            SELECT SUM(ROUND((ayikb_cart_table.cart_qty * ayikb_item_table.price) * 0.1)) 
            FROM ayikb_cart_table 
            JOIN ayikb_item_table 
            ON ayikb_cart_table.item_id = ayikb_item_table.item_id 
            WHERE ayikb_cart_table.id = ".$session_id;
            
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_COLUMN)){
            $subtotal = $row;
        }
            return $subtotal;
        
    } catch (Exception $ex) {
        $pdo->rollBack();
        return $errMsg = $ex->getMessage();     
    }
}
function get_subtotal_of_tax_on_order($pdo, $session_id, $order_num_from_mysql){
    try{
        $sql = "
            SELECT SUM(ROUND((ayikb_order_table.order_qty * ayikb_item_table.price) * 0.1)) 
            FROM ayikb_order_table 
            JOIN ayikb_item_table 
            ON ayikb_order_table.item_id = ayikb_item_table.item_id 
            WHERE ayikb_order_table.id = ".$session_id
            ." AND ayikb_order_table.order_num = '".$order_num_from_mysql."';";
            
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_COLUMN)){
            $subtotal = $row;
        }
            return $subtotal;
        
    } catch (Exception $ex) {
        $pdo->rollBack();
        return $errMsg = $ex->getMessage();     
    }
}
function get_subtotal_of_price_w_tax($pdo, $session_id){ //カートページ用
    try{
        $sql = "
            SELECT SUM(ROUND((ayikb_cart_table.cart_qty * ayikb_item_table.price) * 1.1)) 
            FROM ayikb_cart_table 
            JOIN ayikb_item_table 
            ON ayikb_cart_table.item_id = ayikb_item_table.item_id 
            WHERE ayikb_cart_table.id = ".$session_id;
            
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_COLUMN)){
            $subtotal = $row;
        }
            return $subtotal;
        
    } catch (Exception $ex) {
        $pdo->rollBack();
        return $errMsg = $ex->getMessage();     
    }
}

function get_subtotal_of_price_w_tax_on_order($pdo, $session_id, $order_num_from_mysql){ //オーダーページ用
    try{
        $sql = "
            SELECT SUM(ROUND((ayikb_order_table.order_qty * ayikb_item_table.price) * 1.1)) 
            FROM ayikb_order_table 
            JOIN ayikb_item_table 
            ON ayikb_order_table.item_id = ayikb_item_table.item_id 
            WHERE ayikb_order_table.id = ".$session_id
            ." AND ayikb_order_table.order_num = '".$order_num_from_mysql."';";
            
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_COLUMN)){
            $subtotal = $row;
        }
            return $subtotal;
        
    } catch (Exception $ex) {
        $pdo->rollBack();
        return $errMsg = $ex->getMessage();     
    }
}
function get_subtotal_of_item($pdo, $session_id){ //カートにあるアイテム数の合計を取得
    try{
        $sql = 'SELECT SUM(cart_qty) FROM ayikb_cart_table WHERE id = '.$session_id;
            
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_COLUMN)){
            $subtotal = $row;
        }
            return $subtotal;
        
    } catch (Exception $ex) {
        $pdo->rollBack();
        return $errMsg = $ex->getMessage();     
    }
}
function get_subtotal_of_item_on_order($pdo, $session_id, $order_num_from_mysql){
    try{
        $sql = "
            SELECT SUM(order_qty) FROM ayikb_order_table WHERE id = ".$session_id
            ." AND ayikb_order_table.order_num = '".$order_num_from_mysql."';";
            
        $stmh = $pdo->query($sql);
        while ($row = $stmh -> fetch(PDO::FETCH_COLUMN)){
            $subtotal = $row;
        }
            return $subtotal;
        
    } catch (Exception $ex) {
        $pdo->rollBack();
        return $errMsg = $ex->getMessage();     
    }
}

function compare_cart_qty_and_qty($pdo, $tmp){
    try{
        foreach($tmp as $key){
            if(get_qty($pdo, $key['item_id']) === '0'){
                $errMsg[] = '"'.$key['item_name'].'" is currently unavailable.';
            }
            elseif($key['cart_qty'] > get_qty($pdo, $key['item_id'])){
                $errMsg[] = 'Sorry, "'.$key['item_name'].'" is less than you order.';
            }
            if(get_status($pdo, $key['item_id'])==="0"){
                $errMsg[] = '"'.$key['item_name'].'" is currently unopen.';
            }
        }
        if(isset($errMsg)){
            return $errMsg;  
        }
    } catch (Exception $ex) {
        return $errMsg[] = $ex->getMessage(); 
    }
}
