<?php

require_once '../func_db/mydb.php'; //db読み込み
require_once '../func_db/function.php'; //function読み込み

session_start();

$request_method = get_request_method();
for($i=1;$i<21;$i++){ //カートの数量変更用のarr
    $qtys[$i]=$i;
};

if(isset($_SESSION['id'])===false && isset($_COOKIE['id'])===false){
    header('Location: ./index.php');
    exit;
}

if($request_method==='POST'){
    
    //カートの作成or更新
    try{ 
        if(isset($_POST['type']) && $_POST['type']==='cart'){
            if(!array_key_exists('item_id', $_POST)||empty($_POST['item_id'])){
                throw new Exception('Recevied invalid parameters.');
            }else{
                if(get_qty($pdo, $_POST['item_id'])!=='0'){ //在庫がゼロでなく
                    if(get_status($pdo, $_POST['item_id'])!=='0'){ //非公開でなく
                        if(is_array(get_cart_info_exists($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], $_POST['item_id']))===false){ //cartに同じユーザーで同じ商品がなければ
                            
                            try{//カート新規作成
                                $pdo->beginTransaction();
                                $sql = '
                                    INSERT INTO ayikb_cart_table(id, item_id, cart_qty, created_at) 
                                    VALUES(:id, :item_id, :cart_qty, now())';
                                $stmh = $pdo->prepare($sql);
                                $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
                                $stmh->bindValue(':item_id', $_POST['item_id'], PDO::PARAM_INT);
                                $stmh->bindValue(':cart_qty', 1, PDO::PARAM_INT);
                                $stmh->execute();
                                $pdo->commit();
                            } catch (Exception $ex) {
                                $pdo->rollBack();
                                $errMsg[] = $ex->getMessage();
                            }
                        }else{ //cartに同じユーザーで同じ商品があれば
                            
                            try{//カート更新
                                $pdo->beginTransaction();
                                $sql = '
                                    UPDATE ayikb_cart_table
                                    SET cart_qty = cart_qty + :cart_qty, 
                                    updated_at = now() 
                                    WHERE id = :id AND item_id = :item_id';
                                $stmh = $pdo->prepare($sql);
                                $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
                                $stmh->bindValue(':item_id', $_POST['item_id'], PDO::PARAM_INT);
                                $stmh->bindValue(':cart_qty', 1, PDO::PARAM_INT);
                                $stmh->execute();
                                $pdo->commit();
                            } catch (Exception $ex) {
                                $pdo->rollBack();
                                $errMsg[] = $ex->getMessage();
                            }
                        }
                    }
                }
            }
        }
    } catch (Exception $ex) {
        $pdo->rollBack();
        $errMsg[] = $ex->getMessage();
    }
    
    //カートの数量更新
    try{
        if(isset($_POST['type']) && $_POST['type']==='change_qty'){
            if(!array_key_exists('item_id', $_POST)||empty($_POST['item_id'])){
                throw new Exception('Recevied invalid parameters.');
            }else{
                if(get_qty($pdo, $_POST['item_id'])!=='0'){ //在庫がゼロでなく
                    if(get_status($pdo, $_POST['item_id'])!=='0'){ //非公開でなく
                        
                        try{
                            $pdo->beginTransaction();
                            $sql = '
                                UPDATE ayikb_cart_table
                                SET cart_qty = :change_qty, 
                                updated_at = now() 
                                WHERE id = :id AND item_id = :item_id';
                            $stmh = $pdo->prepare($sql);
                            $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
                            $stmh->bindValue(':item_id', $_POST['item_id'], PDO::PARAM_INT);
                            $stmh->bindValue(':change_qty', $_POST['change_qty'], PDO::PARAM_INT);
                            $stmh->execute();
                            $pdo->commit();
                        } catch (Exception $ex) {
                            $pdo->rollBack();
                            $errMsg[] = $ex->getMessage();
                        }
                    }
                }
            }
        }
    } catch (Exception $ex) {
        $pdo->rollBack();
        echo $errMsg[] = $ex->getMessage();
    }
    
    //カートから削除
    try{
        if(isset($_POST['type']) && $_POST['type']==='delete'){
            if(!array_key_exists('item_id', $_POST)||empty($_POST['item_id'])){
                throw new Exception('Recevied invalid parameters.');
            }else{
                try{
                    $pdo->beginTransaction();
                    $sql = "DELETE FROM ayikb_cart_table WHERE id = :id AND item_id = :item_id;";
                    $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
                    $stmh->bindValue(':item_id', $_POST['item_id'], PDO::PARAM_INT);
                    $stmh->execute();
                    $pdo->commit();
                
                } catch (Exception $ex) {
                    $pdo->rollBack();
                    echo $errMsg[] = $ex->getMessage();
                }
            }
        }
    } catch (Exception $ex) {
        $pdo->rollBack();
        echo $errMsg[] = $ex->getMessage();
    }
    
}

//カート一覧取得
try{
    if(get_cart_exists($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'])===true){ //カートにアイテムがあれば
        try{
            $sql = "
                SELECT ayikb_cart_table.id, ayikb_cart_table.item_id, ayikb_cart_table.cart_qty, 
                ayikb_item_table.price, ayikb_item_table.item_name, ayikb_item_table.qty, 
                ayikb_item_table.status, ayikb_img_table.img_ext, ayikb_img_table.img_raw 
                FROM ayikb_cart_table 
                JOIN ayikb_item_table 
                ON ayikb_cart_table.item_id = ayikb_item_table.item_id 
                JOIN ayikb_img_table 
                ON ayikb_cart_table.item_id = ayikb_img_table.item_id 
                WHERE ayikb_cart_table.id = :id 
                ORDER BY ayikb_cart_table.cart_id desc;";
            $stmh = $pdo->prepare($sql); //bindValueする時は'$pdo->query($sql)'はダメ
            $stmh->bindValue(':id', isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], PDO::PARAM_INT); 
            $stmh->execute();
            while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
                $cart[] = h_arr($row);
            }
        } catch (Exception $ex) {
            $errMsg[] = $ex->getMessage();
        }
    }
} catch (Exception $ex) {
    $errMsg[] = $ex->getMessage();
}

include_once './cart_view.php'; 