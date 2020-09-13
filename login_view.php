<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./styles.css">
        <link href="https://fonts.googleapis.com/css2?family=Major+Mono+Display&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Heebo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <title>Mysore Records Log-in</title>
    </head>
    <body>
        <div class="head_wrapper">
            <header>
                <?php
                include_once './header.php';
                ?>
            </header>
        </div>
        <div class="login_wrapper">
            <div class="login_container">
                <h3>Log-in</h3>
                <div class="form">
                    <form method="POST" enctype="multipart/form-data" action="./login_ctrl.php" id="login">
                        <ul>
                            <li>
                                <label for="email">Email</label>
                                <input type="text" name="email" value="<?php if(isset($_SESSION['email'])){echo $_SESSION['email'];}?>">
                            </li>
                            <li>
                                <label for="passwd">Password</label>
                                <input type="password" name="passwd" placeholder="">
                            </li>
                            <li class="checkbox">
                                    <input type="checkbox" name="checkbox">
                                    Skip entering Email from next time
                            </li>
                        </ul>
                        <input type="hidden" name="type" value="login">
                        <div class="btn_div">
                            <input type="submit" class="btn" value="Continue">
                        </div>
                    </form>
                </div>
            </div>
            <?php if(isset($errMsg) && count($errMsg)>0){
                foreach($errMsg as $key => $val){
                    echo '<div class="notion">'.$val.'</div>';
                }
            }?>
            <div class="border">
                <table>
                    <tr>
                        <td width="150px"><hr color="silver" size="1px"></td>
                        <td>or</td>
                        <td width="150px"><hr color="silver" size="1px"></td>
                    </tr>
                </table>
            </div>
            <div class="move_btn">
                <a href="./create_ctrl.php">Create your account</a>
            </div>
        </div>  
        <div class="foot_wrapper">
            <footer>
                <?php
                include_once './footer.php';
                ?>
            </footer>
        </div>
    </body>
</html>
