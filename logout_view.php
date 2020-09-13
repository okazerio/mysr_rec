<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./styles.css">
        <link href="https://fonts.googleapis.com/css2?family=Major+Mono+Display&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Heebo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <title>Mysore Records Log-out</title>
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
                <h3>Log-out</h3>
                <div class="form">
                    <form method="POST" enctype="multipart/form-data" action="./logout_ctrl.php" id="login">
                        <p style="text-align: center;">Are you sure you want to log-out?</p>
                        <input type="hidden" name="type" value="logout">
                        <div style="text-align: center;">
                            <input type="submit" class="btn" value="Done">
                        </div>
                    </form>
                </div>
            </div>
            <?php if(isset($successMsg) && count($successMsg)>0){
                foreach($successMsg as $key => $val){
                    echo '<div class="notion" style="color:blue">'.$val.'</div>';
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
                <a href="./index.php">Back to Top</a>
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
