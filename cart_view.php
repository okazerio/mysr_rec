<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./styles.css">
        <link href="https://fonts.googleapis.com/css2?family=Major+Mono+Display&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Heebo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <title>Mysore Records Cart</title>
    </head>
    <body>
        <div class="head_wrapper">
            <header>
                <?php include_once './header.php'; ?>
            </header>
        </div>
        <div class="main_wrapper">
            <div class="main_container">
                <h3>Shopping cart</h3>
                <div class="main_content">
                    <?php if(isset($cart)): ?>
                        <div class="cart_list">
                            <?php foreach($cart as $key => $val): ?>
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
                                                <?php if($val['qty']>='20'): ?>
                                                    <small>
                                                        <i style="color: green;">In stock.</i>
                                                    </small>
                                                <?php elseif($val['qty']==='0'): ?>
                                                    <small>
                                                        <i style="color: red;">Currently unavailable. Please delete.</i>
                                                    </small>
                                                <?php elseif($val['qty']<='10'): ?>
                                                    <small>
                                                        <i style="color: red;">Only <?= $val['qty'] ?> left in stock - order soon.</i>
                                                    </small>
                                                <?php endif; ?>
                                            </li>
                                            <li>
                                                <form method="POST" enctype="multipart/form-data" action="./cart_ctrl.php" onchange="submit(this.form)">
                                                    Qty: 
                                                    <select name="change_qty">
                                                        <?php foreach($qtys as $key): ?>
                                                            <option value="<?= $key ?>"<?php if($val['cart_qty']==$key){echo "selected";}?>>
                                                                <?= $key ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <!--<input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">ユーザー情報の送信-->
                                                    <input type="hidden" name="item_id" value="<?= $val['item_id'] ?>"><!--アイテム情報の送信-->
                                                    <input type="hidden" name="type" value="change_qty">
                                                </form>
                                                <form method="POST" enctype="multipart/form-data" action="./cart_ctrl.php">
                                                        <!--<input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">ユーザー情報の送信-->
                                                        <input type="hidden" name="item_id" value="<?= $val['item_id'] ?>">
                                                        <input type="hidden" name="type" value="delete">
                                                        <input type="submit" value="Delete">
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="item_price_in_cart">
                                        Price: <strong>&yen;<?= number_format($val['price']) ?>- </strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div style="width:20px;"></div>
                        <aside>
                            <h4>Order Summary</h4>
                            <div class="subtotal_in_aside">
                                <ul>
                                    <li>
                                        <label class="left">
                                            Subtotal<small>(<?= get_subtotal_of_item($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id']) ?><?= get_subtotal_of_item($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'])>1?'items':'item' ?>)</small>
                                        </label>
                                        <label>:</label>
                                        <label class="money">
                                            <strong>
                                                &yen;<?= number_format(get_subtotal_of_price($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'])) ?>-
                                            </strong>
                                        </label>
                                    </li>
                                    <li class="border-bottom">
                                        <label class="left">
                                            Tax<small>(10%)</small>
                                        </label>
                                        <label>:</label>
                                        <label class="money">
                                            <strong>&yen;<?= number_format(get_subtotal_of_tax($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'])) ?>-</strong>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="left">
                                            Total
                                        </label>
                                        <label>:</label>
                                        <label class="money">
                                            <strong>&yen;<?= number_format(get_subtotal_of_price_w_tax($pdo, isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'])) ?>-</strong>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <?php if(isset($_COOKIE['id'])): ?>
                                <h4>Guest information</h4>
                                <form method="POST" enctype="multipart/form-data" action="./confirm_ctrl.php">
                                    <div class="guest_info_in_aside">
                                        <ul>
                                            <li>
                                                <label class="left" for="guest_name">Name</label>
                                                <label for="guest_name">:</label>
                                                <input type="text" name="guest_name" placeholder="ex) Taro Yamada" value="<?=isset($_POST['guest_name'])?$_POST['guest_name']:''?>">
                                            </li>
                                            <li>
                                                <label  class="left" for="guest_email">Email</label>
                                                <label for="guest_email">:</label>
                                                <input type="text" name="guest_email" placeholder="ex) Taro@zmail.com" value="<?=isset($_POST['guest_email'])?$_POST['guest_email']:''?>">
                                            </li>
                                            <li>
                                                <label class="left" for="guest_zip">Zip code</label>
                                                <label for="guest_zip">:</label>
                                                <input type="text" name="guest_zip" placeholder="ex) 160-0013" value="<?=isset($_POST['guest_zip'])?$_POST['guest_zip']:''?>">
                                            </li>
                                            <li>
                                                <label class="left" for="guest_address">Address</label>
                                                <label for="guest_address">:</label>
                                                <textarea rows="3" cols="115" name="guest_address" placeholder="ex) 3-1, Kasumigaokamachi, Shinjuku Ku, Tokyo, Japan" value="<?=isset($_POST['guest_address'])?$_POST['guest_address']:''?>"></textarea>
                                            </li>
                                            <li>
                                                <label class="left" for="guest_phone">Phone</label>
                                                <label for="guest_phone">:</label>
                                                <input type="text" name="guest_phone" placeholder="ex) 090-1234-5678" value="<?=isset($_POST['guest_phone'])?$_POST['guest_phone']:''?>">
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="confirm_btn_div">
                                            <input type="hidden" name="id" value="<?= isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'] ?>">
                                            <input type="hidden" name="type" value="confirm_from_guest">
                                            <input type="submit" class="confirm_btn" value="Place your order">
                                    </div>
                                </form>
                            <?php else: ?>
                            <div class="confirm_btn_div">
                                <form method="POST" enctype="multipart/form-data" action="./confirm_ctrl.php">
                                    <input type="hidden" name="id" value="<?= isset($_SESSION['id'])?$_SESSION['id']:$_COOKIE['id'] ?>">
                                    <input type="hidden" name="type" value="confirm">
                                    <input type="submit" class="confirm_btn" value="Place your order">
                                </form>
                            </div>
                            <?php endif; ?>
                            <div class="border">
                                <table>
                                    <tr>
                                        <td width="100px"><hr color="silver" size="1px"></td>
                                        <td>or</td>
                                        <td width="100px"><hr color="silver" size="1px"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="top_btn">
                                <a href="./index.php">Back to top</a>
                            </div>
                        </aside>
                    <?php else: ?>
                        <div>
                            <p style="text-align: center;color:blue;">Enjoy shopping here!</p>
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
                    <?php endif; ?> 
                </div>
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
