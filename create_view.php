<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./styles.css">
        <link href="https://fonts.googleapis.com/css2?family=Major+Mono+Display&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Heebo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <title>Mysore Records Create</title>
    </head>
    <body>
        <div class="head_wrapper">
            <header>
                <?php include_once './header.php'; ?>
            </header>
        </div>
        <div class="create_wrapper">
            <div class="create_container">
                <h3>Create your account</h3>
                <div class="form">
                    <form method="POST" enctype="multipart/form-data" action="./create_ctrl.php" id="login">
                        <ul>
                            <li>
                                <label for="name">Name</label>
                                <input type="text" name="name" placeholder="">
                            </li>
                            <li>
                                <label for="email">Email</label>
                                <input type="text" name="email" placeholder="">
                            </li>
                            <li>
                                <label for="passwd">Password</label>
                                <input type="password" name="passwd" placeholder="At least 6 characters">
                            </li>
                        </ul>
                        <input type="hidden" name="type" value="create">
                        <div class="btn_div">
                            <input type="submit" class="btn" value="Create">
                        </div>
                    </form>
                </div>
            </div>
            <?php if(isset($successMsg) && count($successMsg)>0){
                foreach($successMsg as $key => $val){
                    echo '<div class="notion" style="color:blue">'.$val.'</div>';
                }
            }?>
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
                <a href="./login_ctrl.php">Log-in</a>
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