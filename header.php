<div class="header_item">
    <div class="header_top">
        <p class="user_name">Hello, 
                <?php
                    if(isset($_SESSION['id'])){
                        if($_SESSION['name']==="admin"){
                            echo '<span style="color: yellow"><a href="./admin_top_ctrl.php">'.$_SESSION['name'].'</a>';
                        }else{
                            echo '<span style="color: pink">'.$_SESSION['name'];
                        }
                    }else{
                        echo '<span>guest';
                    }
                ?>
            </span>
        !</p>
        <h1><a href="./index.php">Mysore Records</a></h1>
        <a href="./cart_ctrl.php"><span class="fa fa-shopping-cart" title="Cart"></span></a>
        <a href="./login_ctrl.php"><span class="fa fa-user" title="Log-in"></span></a>
        <a href="./logout_ctrl.php"><span class="fa fa-sign-out" title="Log-out"></span></a>  
    </div>
    <div class="header_bottom">
        <ul>
        <li>
            <a href="./index.php">Top</a>
        </li>
        <li>
            <a  onclick="clickEvent()">News</a>
        </li>
        <li>
            <a  onclick="clickEvent()">FAQ</a>
        </li>
        <li>
            <a  onclick="clickEvent()">Contact</a>
        </li>
        </ul>
    </div>
</div>
<script>
    function clickEvent() {
        alert('Under construction.');
        
    }
</script>
    