<?php

require_once '../func_db/mydb.php'; //db読み込み
require_once '../func_db/function.php'; //function読み込み

$request_method = get_request_method();

session_start();

if($request_method==='POST'){
    try{
        if(isset($_POST['type']) && $_POST['type']==='login'){
            if($_POST['email']==='' || $_POST['passwd']===''){
                
                throw new Exception('Input fields are all required.');
                
            }else{
                $sql = 'SELECT id, name, email, passwd FROM ayikb_user_table WHERE email = :email';
                $stmh = $pdo->prepare($sql); //prepareメソッドの実行
                $stmh->bindValue(':email', $_POST['email'], PDO::PARAM_STR); //データ型は文字列
                $stmh->execute();
                $row = $stmh->fetch(PDO::FETCH_ASSOC); //データを取得して変数に代入

                if(is_array($row)===false){ //メールアドレスが登録と一致しない場合
                    throw new Exception('Email is wrong.');
                }else{
                    
                    $row = h_arr($row);

                    if($_POST['passwd'] === $row['passwd']){

                        if(isset($_POST['checkbox']) && $_POST['checkbox']==='on'){ //次回ログイン時に入力を省略するため変数へ代入
//                            setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 365, '/'); //COOKIEにてemailを保存 次回入力回避用
                            $_SESSION['email'] = $row['email']; //session変数に代入
                            
                        }

                        $_SESSION['id'] = $row['id']; //session変数に代入
                        $_SESSION['name'] = $row['name']; //session変数に代入

                        switch($_SESSION['id']){
                            case $_SESSION['id']==='1';
                               header('Location: ./admin_top_ctrl.php');// 管理人をadminページへ遷移
                               break;
                            case $_SESSION['id']!=='1';
                               header('Location: ./index.php');// ユーザーをトップページへ遷移
                               break;
                        }

                    }else{ //パスが登録と一致しない場合
                            throw new Exception('Password is wrong.');
                    }
                }
            }
        }
    } catch (Exception $ex) {
        $errMsg[] = $ex->getMessage();
    }
}

include_once './login_view.php'; 
