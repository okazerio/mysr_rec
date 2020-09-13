<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./styles.css">
        <link href="https://fonts.googleapis.com/css2?family=Major+Mono+Display&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Heebo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <title>Mysore Records Top</title>
    </head>
    <body>
        <div class="head_wrapper">
            <header>
                <?php include_once './header.php'; ?>
            </header>
        </div>
        <div class="main_wrapper">
            <div class="main_container">
                <h3>Item list</h3>
                <div class="select">
                    <p>
                        <?=$total_lines?> items in total, from <?= $offset+1 ?> to 
                        <?php if($page==$total_pages){
                            echo $total_lines;
                        }else{echo $offset+1+$lines_per_page-1;}
                        ?>
                    </p>
                    <small class="small_select">Number of items:</small>
                    <form method="GET" enctype="multipart/form-data" action="./index.php" onchange="submit(this.form)">
                        <select name="limit">
                        <?php foreach($limits as $key => $limit): ?>
                            <option value="<?= $key ?>"<?php if($lines_per_page===$key){echo 'selected';} ?>>
                                <?= $limit ?>
                            </option>
                        <?php endforeach; ?>
                        </select> 
                    </form>
                </div>
                <div class="item_list">
                    <?php foreach($item as $key => $val): ?>
                        <div class="item">
                            <div class="item_img">
                                <img src="data:<?= $val['img_ext'] ?>;base64,<?php echo base64_encode($val['img_raw']) ;?>" alt="<?= $val['item_name'] ?>">
                                <?php if($val['qty']==='0'): ?>
                                    <img src="./img/soldout.png" alt="" class="soldout">
                                <?php endif; ?>
                            </div>
                            <div class="item_name" title="<?= $val['item_name'] ?>">
                                <?= $val['item_name'] ?>
                            </div>
                            <div class="item_price">
                                &yen;<?= number_format($val['price']) ?>-
                            </div>
                            <?php if($val['qty']!=='0'): ?>
                                <form method="POST" enctype="multipart/form-data" action="./cart_ctrl.php">
                                    <input type="hidden" name="item_id" value="<?= $val['item_id'] ?>"><!--アイテム情報の送信-->
                                    <input type="hidden" name="type" value="cart">
                                    <input type="submit" value="&#xf218;" class="fa" style="background-color:transparent;">
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="paging">
                    <?php if ($page > 1) : ?>
                        <a href="?page=<?= $page-1; ?>">&lt;&lt;</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <a href="?page=<?= $i; ?>"><?= $i; ?></a>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages) : ?>
                        <a href="?page=<?= $page+1; ?>">&gt;&gt;</a>
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
