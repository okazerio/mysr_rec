<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./styles_admin.css">
        <link href="https://fonts.googleapis.com/css2?family=Major+Mono+Display&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Heebo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <title>Mysore Records Admin Item</title>
    </head>
    <body>
        <div class="head_wrapper">
            <header>
                <?php
                include_once './header.php';
                ?>
            </header>
        </div>
        <div class="main_wrapper">
            <div class="main_container">
                <nav>
                    <?php 
                    include_once './nav.php';
                    ?>
                </nav>
                <div class="main_content">
                    <div class="form">
                        <h3>Add new item</h3>
                        <form method="POST" enctype="multipart/form-data" action="./admin_item_ctrl.php">
                            <ul>
                                <li>
                                    <label for="item_name">Item name</label>
                                    <input type="text" name="item_name">
                                </li>
                                <li>
                                    <label for="price">Price</label>
                                    <input type="text" name="price">
                                </li>
                                <li>
                                    <label for="qty">Quantity</label>
                                    <input type="text" name="qty">
                                </li>
                                <li>
                                    <label for="img">Image(1MB)</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
                                    <input type="file" name="img">
                                </li>
                                <li>
                                    <label for="status">Status</label>
                                    Open(1)<input type="radio" name="status" value="1" checked="checked">
                                    &ensp;
                                    Unopen(0)<input type="radio" name="status" value="0">
                                </li>
                                <li>
                                    <label for="">&ensp;</label>&ensp;
                                </li>
                                <li>
                                <input type="hidden" name="type" value="add">
                                <input type="submit" value="Add" class="btn">
                                </li>
                            </ul>
                        </form>
                    </div>
                    <?php if(isset($errMsg) && count($errMsg)>0){
                        foreach($errMsg as $key => $val){
                            echo '<div class="notion">'.$val.'</div>';
                        }
                    }?>
                    <div class="border">
                        <table>
                            <tr>
                                <td width="400px"><hr color="silver" size="1px"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="item_list">
                        <h3>Item list</h3>
                        <table border ="1">
                            <thead>
                                <tr>
                                    <th>Item id</th>
                                    <th>Image</th>
                                    <th>Item name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($item as $key => $val): ?>
                                    <tr <?php if($val['status']==='0'){echo 'class="unopen"';}?>>
                                        <td class="item_id"><?= $val['item_id'] ?></td>
                                        <td class="img">
                                            <img src="data:<?=$val['img_ext']?>;base64,<?php echo base64_encode($val['img_raw']);?>">
                                        </td>
                                        <td><?= $val['item_name'] ?></td>
                                        <form method="POST" action="./admin_item_ctrl.php">
                                            <td class="price">
                                                &yen;<input class="qty" type="text" name="price" value="<?= number_format($val['price']) ?>">
                                                <input type="hidden" name="item_id" value="<?= $val['item_id'] ?>">
                                                <input type="hidden" name="type" value="update_price">
                                                <input class="update_btn" type="submit" value="Update">
                                            </td>
                                        </form>
                                        <form method="POST" action="./admin_item_ctrl.php">
                                            <td class="qty_sta_del">
                                                <input class="qty" type="text" name="qty" value="<?= number_format($val['qty']) ?>">
                                                <input type="hidden" name="item_id" value="<?= $val['item_id'] ?>">
                                                <input type="hidden" name="type" value="update_qty">
                                                <input class="update_btn" type="submit" value="Update">
                                            </td>
                                        </form>
                                        <form method="POST" action="./admin_item_ctrl.php">      
                                            <td class="qty_sta_del">
                                                <input type="hidden" name="status" value="<?php if($val['status'] === '1'){echo '0';}else{echo '1';} ?>">
                                                <input type="hidden" name="item_id" value="<?= $val['item_id'] ?>">
                                                <input type="hidden" name="type" value="update_status">
                                                <input type="submit" value="<?=$val['status']?>&rarr;<?=$val['status'] === '1'?'0':'1';?>">
                                            </td>
                                        </form>
                                        <form method="POST" action="./admin_item_ctrl.php">
                                            <td class="qty_sta_del">
                                                <input type="hidden" name="item_id" value="<?= $val['item_id'] ?>">
                                                <input type="hidden" name="type" value="delete">
                                                <input type="submit" value="&#215;">
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
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
