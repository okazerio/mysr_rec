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

if($_SESSION['id']!=='1'){
    header('Location: ./index.php');
    exit;
}

if($request_method==='POST'){
    
    try{
        //ユーザー新規追加
        if(isset($_POST['type']) && $_POST['type']==='add'){
            if($_POST['name']==='' || $_POST['email']==='' || $_POST['passwd']===''){
                throw new Exception('Input fields are all required.');
            }else{
                
                try{
                    $pdo->beginTransaction();

                    $sql = "INSERT INTO ayikb_user_table(name, email, passwd, created_at) "
                            . "VALUES (:name, :email, :passwd, now());";
                    $stmh = $pdo->prepare($sql);
                    $stmh -> bindValue(":name",$_POST['name'], PDO::PARAM_STR);
                    $stmh -> bindValue(":email",$_POST['email'], PDO::PARAM_STR);
                    $stmh -> bindValue(":passwd",$_POST['passwd'], PDO::PARAM_STR);
                    $stmh->execute();
                    $pdo->commit();

                } catch (Exception $ex) {
                    $pdo->rollBack();
                    $errMsg[] = $ex->getMessage();
                }
            }
        }
        
        //ユーザーステータス変更
        if(isset($_POST['type']) && $_POST['type']==='edit'){
            if($_POST['id']==='' || $_POST['name']==='' || $_POST['email']==='' || $_POST['passwd']===''){
                throw new Exception('Input fields are all required.');
            }else{
                
                try{
                    $pdo->beginTransaction();

                    $sql = "
                        UPDATE ayikb_user_table 
                        SET name = :name, email = :email, passwd = :passwd, updated_at = now() 
                        WHERE id = :id;";

                    $stmh = $pdo->prepare($sql);
                    $stmh -> bindValue(":name",$_POST['name'], PDO::PARAM_STR);
                    $stmh -> bindValue(":email",$_POST['email'], PDO::PARAM_STR);
                    $stmh -> bindValue(":passwd",$_POST['passwd'], PDO::PARAM_STR);
                    $stmh -> bindValue(":id",$_POST['id'], PDO::PARAM_INT);
                    $stmh->execute();
                    $pdo->commit();

                } catch (Exception $ex) {
                    $pdo->rollBack();
                    $errMsg[] = $ex->getMessage();
                }
            }
        } 
        
        //ユーザー削除
        if(isset($_POST['type']) && $_POST['type']==='delete'){
            if($_POST['id']===''){
                throw new Exception('Invalid parameter.');
            }else{
                try{
                    $pdo->beginTransaction();
                    $sql = "DELETE FROM ayikb_user_table WHERE id = :id;";
                    $stmh = $pdo->prepare($sql);
                    $stmh -> bindValue(":id",(int)$_POST['id'], PDO::PARAM_INT);
                    $stmh->execute();
                    $pdo->commit();
                } catch (Exception $ex) {
                    $pdo->rollBack();
                    $errMsg[] = $ex->getMessage();
                }
            }
        }
    } catch (Exception $ex) {
        $errMsg[] = $ex->getMessage();
    }
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
    $tmp = array_keys($limits);//なかったら$limitsの最初のキー「5」をセット
    $lines_per_page = (int)array_shift($tmp);//なかったら$limitsの最初のキー「5」をセット
    
}

$offset = $lines_per_page * ($page - 1);


//ユーザー一覧取得
$sql = "
       SELECT id, name, email, passwd, created_at 
       FROM ayikb_user_table 
       ORDER BY ayikb_user_table.id desc 
       LIMIT ".$offset.", ".$lines_per_page;
$stmh = $pdo->query($sql);
while ($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
    $user[]= h_arr($row);
}
//ユーザー件数取得
$sql = "SELECT COUNT(*) FROM ayikb_user_table;";
$stmh = $pdo->query($sql);
$total_lines = $stmh->fetchColumn();
$total_pages = ceil($total_lines / $lines_per_page); //ceilで切り上げ



include_once './admin_user_view.php'; 