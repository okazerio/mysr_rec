<?php

require_once '../func_db/mydb.php'; //db読み込み
require_once '../func_db/function.php'; //function読み込み

$request_method = get_request_method();
session_start();

if(isset($_SESSION['id'])===false && isset($_COOKIE['id'])===false){
    header('Location: ./index.php');
    exit;
}elseif($request_method!=='POST'){
    header('Location: ./index.php');
    exit;
}


if($request_method==='POST'){
    
    
    
    try{
    
        //ゲストからのオーダー
        if(isset($_POST['type']) && $_POST['type']==='confirm_from_guest'){
            
            
            
            if($_POST['guest_name']==='' || $_POST['guest_email']==='' 
            || $_POST['guest_zip']==='' || $_POST['guest_address']==='' 
            || $_POST['guest_phone']===''){
                throw new Exception('Guest information\'s input fields are all required.');
            }else{
                
                $guest=[ //ゲストが購入したときに表示する情報
                    'guest_name'=>h($_POST['guest_name']),
                    'guest_email'=>h($_POST['guest_email']),
                    'guest_zip'=>h($_POST['guest_zip']),
                    'guest_address'=>h($_POST['guest_address']),
                    'guest_phone'=>h($_POST['guest_phone']),
                    ];
                
                $tmp=get_cart_info($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id']);
                
                if(is_array(compare_cart_qty_and_qty($pdo, $tmp))===false){ //全カートアイテムの在庫が有り、かつ公開商品であるか
                    $order_num = uniqid(rand()); //購入履歴表示用のグルーピングid
                    foreach($tmp as $key){
                        try{ 
                            $pdo->beginTransaction();
                            
                            //在庫減少
                            $sql = '
                                UPDATE ayikb_item_table
                                SET qty = qty - :cart_qty, 
                                updated_at = now() 
                                WHERE item_id = :item_id'; 
                            $stmh = $pdo->prepare($sql);
                            $stmh->bindValue(':cart_qty', $key['cart_qty'], PDO::PARAM_INT);
                            $stmh->bindValue(':item_id', $key['item_id'], PDO::PARAM_INT);
                            $stmh->execute();
                            
                            //購入確定
                            $sql = "
                                INSERT INTO ayikb_order_table(id, item_id, order_qty, order_num, created_at) 
                                VALUES(:id, :item_id, :order_qty, :order_num, now())";
                            $stmh = $pdo->prepare($sql);
                            $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
                            $stmh->bindValue(':item_id', $key['item_id'], PDO::PARAM_INT); 
                            $stmh->bindValue(':order_qty', $key['cart_qty'], PDO::PARAM_INT); 
                            $stmh->bindValue(':order_num', $order_num, PDO::PARAM_STR); 
                            $stmh->execute();
                            $order_id=$pdo->lastInsertId();
                            $order_num_from_mysql = get_order_num($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], $order_id);
                            
                            //カート削除
                            $sql = "DELETE FROM ayikb_cart_table WHERE id = :id;";
                            $stmh = $pdo->prepare($sql);
                            $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
                            $stmh->execute();
                            
                            $pdo->commit();
                            
                        } catch (Exception $ex) {
                            $pdo->rollBack();
                            $errMsg[] = $ex->getMessage();
                        }
                    }
                }else{
                    $errMsg = compare_cart_qty_and_qty($pdo, $tmp);
                }
            }
        }else{
            
            //会員からのオーダー
            if(isset($_POST['type']) && $_POST['type']==='confirm'){
                
                $tmp=get_cart_info($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id']);
                
                if(is_array(compare_cart_qty_and_qty($pdo, $tmp))===false){ //全カートアイテムの在庫が有り、かつ公開商品であるか
                    $order_num = uniqid(rand()); //購入履歴表示用のグルーピングid
                    foreach($tmp as $key){
                        try{ 
                            $pdo->beginTransaction();
                            
                            //在庫減少
                            $sql = '
                                UPDATE ayikb_item_table
                                SET qty = qty - :cart_qty, 
                                updated_at = now() 
                                WHERE item_id = :item_id'; 
                            $stmh = $pdo->prepare($sql);
                            $stmh->bindValue(':cart_qty', $key['cart_qty'], PDO::PARAM_INT);
                            $stmh->bindValue(':item_id', $key['item_id'], PDO::PARAM_INT);
                            $stmh->execute();
                            
                            //購入確定
                            $sql = "
                                INSERT INTO ayikb_order_table(id, item_id, order_qty, order_num, created_at) 
                                VALUES(:id, :item_id, :order_qty, :order_num, now())";
                            $stmh = $pdo->prepare($sql);
                            $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
                            $stmh->bindValue(':item_id', $key['item_id'], PDO::PARAM_INT); 
                            $stmh->bindValue(':order_qty', $key['cart_qty'], PDO::PARAM_INT); 
                            $stmh->bindValue(':order_num', $order_num, PDO::PARAM_STR); 
                            $stmh->execute();
                            $order_id=$pdo->lastInsertId();
                            $order_num_from_mysql = get_order_num($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], $order_id);
                            
                            //カート削除
                            $sql = "DELETE FROM ayikb_cart_table WHERE id = :id;";
                            $stmh = $pdo->prepare($sql);
                            $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
                            $stmh->execute();
                            
                            $pdo->commit();
                            
                        } catch (Exception $ex) {
                            $pdo->rollBack();
                            $errMsg[] = $ex->getMessage();
                        }
                    }
                }else{
                    $errMsg = compare_cart_qty_and_qty($pdo, $tmp);
                }
            }
        }
    }
    catch (Exception $ex) {
        $errMsg[] = $ex->getMessage();
    }
}         
    

//オーダ一覧取得
try{
    if(isset($order_num_from_mysql)){ //注文番号を取得（購入確定）していれば
        try{
            $sql = "
                SELECT ayikb_order_table.order_num, ayikb_order_table.order_qty, ayikb_item_table.price, 
                ayikb_item_table.item_name, ayikb_img_table.img_ext, ayikb_img_table.img_raw 
                FROM ayikb_order_table 
                JOIN ayikb_item_table 
                ON ayikb_order_table.item_id = ayikb_item_table.item_id 
                JOIN ayikb_img_table 
                ON ayikb_order_table.item_id = ayikb_img_table.item_id 
                WHERE ayikb_order_table.id = :id 
                AND ayikb_order_table.order_num = '".$order_num_from_mysql."' 
                ORDER BY ayikb_order_table.order_id desc";
                $stmh = $pdo->prepare($sql); //bindValueする時は'$pdo->query($sql)'はダメ
                $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
                $stmh->execute();
                while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
                    $order[]= h_arr($row);
                }
        } catch (Exception $ex) {
            $errMsg[] = $ex->getMessage();
        }
    }
} catch (Exception $ex) {
    $errMsg[] = $ex->getMessage();
}

include_once './confirm_view.php'; 