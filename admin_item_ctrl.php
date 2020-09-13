<?php

require_once '../func_db/mydb.php'; //db読み込み
require_once '../func_db/function.php'; //function読み込み

//$pdo = db_connect();
$request_method = get_request_method();

session_start();

if($_SESSION['id']!=='1'){
    header('Location: ./index.php');
    exit;
}

if($request_method==='POST'){
    
    //新規追加
    if(isset($_POST['type']) && $_POST['type']==='add'){
        if($_POST['item_name']!=='' && $_POST['price']!=='' && $_POST['qty']!=='' 
        && $_FILES['img']['error']===0){
            
            try{
                $pdo->beginTransaction();

                //アイテム登録
                $sql = "INSERT INTO ayikb_item_table(item_name, price, qty, status, created_at) "
                        . "VALUES (:item_name, :price, :qty, :status, :created_at);";
                $stmh = $pdo->prepare($sql);
                $stmh -> bindValue(":item_name",$_POST['item_name'], PDO::PARAM_STR);
                $stmh -> bindValue(":price",(int)$_POST['price'], PDO::PARAM_INT);
                $stmh -> bindValue(":qty",(int)$_POST['qty'], PDO::PARAM_INT);
                $stmh -> bindValue(":status",(int)$_POST['status'], PDO::PARAM_INT);
                $stmh -> bindValue(":created_at",date('Y/m/d H:i:s'), PDO::PARAM_STR);

                $stmh->execute();

                $item_id = $pdo->lastInsertId(); //item_id 下記で使用

                //画像登録
                $raw_data = file_get_contents($_FILES['img']['tmp_name']);
                $tmp = pathinfo($_FILES["img"]["name"]);
                $extension = $tmp["extension"];

                $date = getdate();
                $fname = $_FILES["img"]["tmp_name"].$date["year"].$date["mon"]
                        .$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];
                $fname = hash("sha256", $fname);
                $sql = "INSERT INTO ayikb_img_table(item_id, img_name, img_ext, img_raw, created_at) "
                        . "VALUES (:item_id, :img_name, :img_ext, :img_raw, :created_at);";

                $stmh = $pdo->prepare($sql);
                $stmh -> bindValue(":item_id",(int)$item_id, PDO::PARAM_INT);
                $stmh -> bindValue(":img_name",$fname, PDO::PARAM_STR);
                $stmh -> bindValue(":img_ext",$extension, PDO::PARAM_STR);
                $stmh -> bindValue(":img_raw",$raw_data, PDO::PARAM_STR);
                $stmh -> bindValue(":created_at",date('Y/m/d H:i:s'), PDO::PARAM_STR);

                $stmh->execute();
                $pdo->commit();

            } catch (Exception $ex) {
                $pdo->rollBack();
                $errMsg[] = $ex->getMessage();
            }
        }else{
            $errMsg[] = 'All input fields are required.';
        }
    }
    
    //単価変更
    if(isset($_POST['type']) && $_POST['type']==='update_price'){
        if($_POST['price']!==''){
//            var_dump($_POST);
            try{
                $pdo->beginTransaction();
                $sql = "UPDATE ayikb_item_table SET price = :price, updated_at = :updated_at WHERE item_id = :item_id;";
                $stmh = $pdo->prepare($sql);
                $stmh -> bindValue(":item_id",(int)$_POST['item_id'], PDO::PARAM_INT);
                $stmh -> bindValue(":price",(int)$_POST['price'], PDO::PARAM_INT);
                $stmh -> bindValue(":updated_at",date('Y/m/d H:i:s'), PDO::PARAM_STR);

                $stmh->execute();
                $pdo->commit();
                
            } catch (Exception $ex) {
                $pdo->rollBack();
                $errMsg[] = $ex->getMessage();
            }
        }
    }

    //数量変更
    if(isset($_POST['type']) && $_POST['type']==='update_qty'){
        if($_POST['qty']!==''){
            try{
                $pdo->beginTransaction();
                $sql = "UPDATE ayikb_item_table SET qty = :qty, updated_at = :updated_at WHERE item_id = :item_id;";
                $stmh = $pdo->prepare($sql);
                $stmh -> bindValue(":item_id",(int)$_POST['item_id'], PDO::PARAM_INT);
                $stmh -> bindValue(":qty",(int)$_POST['qty'], PDO::PARAM_INT);
                $stmh -> bindValue(":updated_at",date('Y/m/d H:i:s'), PDO::PARAM_STR);

                $stmh->execute();
                $pdo->commit();
                
            } catch (Exception $ex) {
                $pdo->rollBack();
                $errMsg[] = $ex->getMessage();
            }
        }
    }
    
    //ステータス変更(公開・非公開)
    if(isset($_POST['type']) && $_POST['type']==='update_status'){
        if($_POST['status']!==''){
            try{
                $pdo->beginTransaction();
                $sql = "UPDATE ayikb_item_table SET status = :status, updated_at = :updated_at WHERE item_id = :item_id;";
                $stmh = $pdo->prepare($sql);
                $stmh -> bindValue(":item_id",(int)$_POST['item_id'], PDO::PARAM_INT);
                $stmh -> bindValue(":status",(int)$_POST['status'], PDO::PARAM_INT);
                $stmh -> bindValue(":updated_at",date('Y/m/d H:i:s'), PDO::PARAM_STR);

                $stmh->execute();
                $pdo->commit();
                
            } catch (Exception $ex) {
                $pdo->rollBack();
                $errMsg[] = $ex->getMessage();
            }
        }
    }
    
    //削除
    if(isset($_POST['type']) && $_POST['type']==='delete'){
        if($_POST['item_id']!==''){
            try{
                $pdo->beginTransaction();
                $sql = "DELETE FROM ayikb_item_table WHERE item_id = :item_id;";
                $stmh = $pdo->prepare($sql);
                $stmh -> bindValue(":item_id",(int)$_POST['item_id'], PDO::PARAM_INT);

                $stmh->execute();
                
                $sql = "DELETE FROM ayikb_img_table WHERE item_id = :item_id;";
                $stmh = $pdo->prepare($sql);
                $stmh -> bindValue(":item_id",(int)$_POST['item_id'], PDO::PARAM_INT);

                $stmh->execute();
                $pdo->commit();
                
            } catch (Exception $ex) {
                $pdo->rollBack();
                $errMsg[] = $ex->getMessage();
            }
        }
    }

    
}

//アイテム一覧取得
$sql = "
    SELECT ayikb_item_table.item_id, ayikb_item_table.item_name, ayikb_item_table.price, 
    ayikb_item_table.qty, ayikb_item_table.status, 
    ayikb_img_table.img_ext, ayikb_img_table.img_raw 
    FROM ayikb_item_table 
    JOIN ayikb_img_table 
    ON ayikb_item_table.item_id = ayikb_img_table.item_id 
    ORDER BY ayikb_item_table.item_id desc;";
$stmh = $pdo->query($sql);
while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
//    var_dump(h_assoc_arr($row));
    $item[] = h_arr($row);
//    var_dump($item);
}
//var_dump($item);

include_once './admin_item_view.php'; 