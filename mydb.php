<?php

function db_connect(){
    define('DSN', 'mysql:host=us-cdbr-east-02.cleardb.com;dbname=heroku_b59b240f32425cb;cahrset=utf8');
    define('DB_USER', 'b283f2fd251785');
    define('DB_PASSWD', '19314136');
    
    try{
        $pdo = new PDO(DSN,DB_USER,DB_PASSWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $Exception) {
        die('Error of connection to MYSQL:'. $Exception->getMessage());
    }
    return $pdo;
}
