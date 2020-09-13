<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./styles.css">
        <link href="https://fonts.googleapis.com/css2?family=Major+Mono+Display&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Heebo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <title>Mysore Records Confirm</title>
    </head>
    <body>
        <div class="head_wrapper">
            <header>
                <?php include_once './header.php'; ?>
            </header>
        </div>
        <div class="main_wrapper">
            <div class="main_container">
                <?php if(isset($errMsg) && count($errMsg)>0):?>
                    <h3>Thank you for your order! However,,,</h3>
                    <?php
                        foreach($errMsg as $key => $val){
                        echo '<div class="notion">'.$val.'</div>';
                        }
                    ?>
                    <div class="cart_btn">
                        <a href="./cart_ctrl.php">Shopping Cart</a>
                    </div>
                <?php else:?>
                    <h3>Thank you for your order!</h3>
                    <p style="text-align: center;">Order number: <?= $order_num_from_mysql ?><p>
                    <div class="main_content">
                        <div class="confirm_list">
                            <h3>Ordered items</h3>
                            <div class="confirm">
                                <?php foreach($order as $key => $val): ?>
                                    <div class="cart">
                                        <div class="item_img_in_cart">
                                            <img src="data:<?= $val['img_ext'] ?>;base64,<?php echo base64_encode($val['img_raw']) ;?>">
                                        </div>
                                        <div class="item_name_and_qty_in_cart">
                                            <ul>
                                                <li style="color: indigo;">
                                                    <strong><?= $val['item_name'] ?></strong>
                                                </li>
                                                <li>
                                                    Qty: <?= $val['order_qty'] ?>
                                                </li>
                                                <li>　</li>
                                                <li style="color: blue;">
                                                    Arrives in 3 to 4 business days
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="item_price_in_cart">
                                        Price: <strong>&yen;<?= number_format($val['price']) ?>- </strong>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div style="width:20px;"></div>
                        <aside class="confirm">
                            <h4>Order summary</h4>
                            <div class="subtotal_in_aside">
                                <ul>
                                    <li>
                                        <label class="left">
                                            Subtotal<small>(<?= get_subtotal_of_item_on_order($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], $order_num_from_mysql)?><?= get_subtotal_of_item_on_order($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], $order_num_from_mysql)>1?'items':'item' ?>)</small>
                                        </label>
                                        <label>:</label>
                                        <label class="money">
                                            <strong>
                                                &yen;<?= number_format(get_subtotal_of_price_on_order($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], $order_num_from_mysql)) ?>-
                                            </strong>
                                        </label>
                                    </li>
                                    <li class="border-bottom">
                                        <label class="left">
                                            Tax<small>(10%)</small>
                                        </label>
                                        <label>:</label>
                                        <label class="money">
                                            <strong>&yen;<?= number_format(get_subtotal_of_tax_on_order($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], $order_num_from_mysql)) ?>-</strong>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="left">
                                            Total
                                        </label>
                                        <label>:</label>
                                        <label class="money">
                                            <strong>&yen;<?= number_format(get_subtotal_of_price_w_tax_on_order($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'], $order_num_from_mysql)) ?>-</strong>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <?php if(isset($_COOKIE['id'])): ?>
                                <h4>Guest information</h4>
                                <div class="guest_info_in_aside">
                                    <ul>
                                        <li>
                                            <label class="left" for="guest_name">Name</label>
                                            <label for="guest_name">:</label>
                                            <p><?=$guest['guest_name']?></p>
                                        </li>
                                        <li>
                                            <label  class="left" for="guest_email">Email</label>
                                            <label for="guest_email">:</label>
                                            <p><?=$guest['guest_email']?></p>
                                        </li>
                                        <li>
                                            <label class="left" for="guest_zip">Zip code</label>
                                            <label for="guest_zip">:</label>
                                            <p><?=$guest['guest_zip']?></p>
                                        </li>
                                        <li>
                                            <label class="left" for="guest_address">Address</label>
                                            <label for="guest_address">:</label>
                                            <p><?=$guest['guest_address']?></p>
                                        </li>
                                        <li>
                                            <label class="left" for="guest_phone">Phone</label>
                                            <label for="guest_phone">:</label>
                                            <p><?=$guest['guest_phone']?></p>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </aside>
                    </div>
                <?php endif; ?>    
            </div>
            <div class="long_border">
                <table>
                    <tr>
                        <td width="900px"><hr color="silver" size="1px"></td>
                    </tr>
                </table>
            </div>
            <div class="top_btn">
                <a href="./index.php">Back to top</a>
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
