<?php

require_once '../func_db/mydb.php'; //db読み込み
require_once '../func_db/function.php'; //function読み込み

$request_method = get_request_method();
$date = date('Y/m/d H:i:s');

session_start();

if($request_method==='POST'){
    try{
        if(isset($_POST['type']) && $_POST['type']==='create'){
            if($_POST['name']==='' || $_POST['email']==='' || $_POST['passwd']===''){
                
                throw new Exception('Input fields are all required.');
                    
            }else if(preg_match('/^[0-9a-zA-Z]{6,}$/',$_POST['passwd'])===0){
                
                throw new Exception('Only at least 6 single-byte alphanumeric characters.');
                
            }else{
                
                try{
                    $pdo->beginTransaction(); //トランザクションの開始
                    $sql = 'INSERT INTO ayikb_user_table(name, email, passwd, created_at) VALUES(:name, :email, :passwd, :created_at)';
                    $stmh = $pdo->prepare($sql); //prepareメソッドの実行
                    $stmh->bindValue(':name', $_POST['name'], PDO::PARAM_STR); //データ型は文字列
                    $stmh->bindValue(':email', $_POST['email'], PDO::PARAM_STR); //データ型は文字列
                //    $stmh->bindValue(':passwd', password_hash($_POST['passwd'], PASSWORD_DEFAULT), PDO::PARAM_STR); //ハッシュ化
                    $stmh->bindValue(':passwd', $_POST['passwd'], PDO::PARAM_STR); //データ型は文字列
                    $stmh->bindValue(':created_at', $date, PDO::PARAM_STR); //データ型は文字列
                    $stmh->execute();
                    $user_id=$pdo->lastInsertId();

                    $pdo->commit();

                    $successMsg[] = 'Created your account. Please log-in below.';
                    
                } catch (Exception $ex) {
                    $pdo->rollBack();
                    $errMsg[] = $ex->getMessage();
                }
                
                if(!isset($user_id)){ //emailはuniqueなので重複のお知らせ
                    $sql = 'SELECT email FROM ayikb_user_table WHERE email = :email';
                    $stmh = $pdo->prepare($sql); //prepareメソッドの実行
                    $stmh->bindValue(':email', $_POST['email'], PDO::PARAM_STR); //データ型は文字列
                    $stmh->execute();
                    $row = $stmh->fetch(PDO::FETCH_ASSOC); //データを取得して変数に代入
                    throw new Exception('\''.$row['email'].'\' is already used.');
                }
                
            }
        }
    } catch (Exception $ex) {
        $errMsg[] = $ex->getMessage();
    }
}

include_once './create_view.php'; 