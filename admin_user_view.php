<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./styles_admin.css">
        <link href="https://fonts.googleapis.com/css2?family=Major+Mono+Display&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Heebo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <title>Mysore Records Admin User</title>
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
                    <div class="form_user_container">
                        <div class="form_user">
                            <h3>Add new user</h3>
                            <form method="POST" enctype="multipart/form-data" action="./admin_user_ctrl.php">
                                <ul>
                                    <li>
                                        <label for="name">Name</label>
                                        <input type="text" name="name">
                                    </li>
                                    <li>
                                        <label for="email">Email</label>
                                        <input type="text" name="email">
                                    </li>
                                    <li>
                                        <label for="passwd">Password</label>
                                        <input type="text" name="passwd">
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
                        <div class="form_user">
                            <h3>Edit user</h3>
                            <form method="POST" enctype="multipart/form-data" action="./admin_user_ctrl.php">
                                <ul>
                                    <li>
                                        <label for="name">ID</label>
                                        <input type="text" name="id">
                                    </li>
                                    <li>
                                        <label for="name">Re-Name</label>
                                        <input type="text" name="name">
                                    </li>
                                    <li>
                                        <label for="email">Re-Email</label>
                                        <input type="text" name="email">
                                    </li>
                                    <li>
                                        <label for="passwd">Re-Password</label>
                                        <input type="text" name="passwd">
                                    </li>
                                    <li>
                                        <label for="">&ensp;</label>&ensp;
                                    </li>
                                    <li>
                                    <input type="hidden" name="type" value="edit">
                                    <input type="submit" value="Edit" class="btn">
                                    </li>
                                </ul>
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
                                <td width="400px"><hr color="silver" size="1px"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="user_list">
                        <h3>User list</h3>
                        <div class="select">
                            <p>
                                <?=$total_lines?> members in total, from <?= $offset+1 ?> to 
                                <?php if($page==$total_pages){
                                    echo $total_lines;
                                }else{echo $offset+1+$lines_per_page-1;}
                                ?>
                            </p>
                            <form method="GET" enctype="multipart/form-data" action="./admin_user_ctrl.php" onchange="submit(this.form)">
                                <select name="limit">
                                <?php foreach($limits as $key => $limit): ?>
                                    <option value="<?= $key ?>"<?php if($lines_per_page===$key){echo 'selected';} ?>>
                                        <?= $limit ?>
                                    </option>
                                <?php endforeach; ?>
                                </select> 
                            </form>
                        </div>
                        <table border ="1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Created at</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($user as $key => $val): ?>
                                    <tr <?php if($val['id']==='1'){echo 'class="admin"';}?>>
                                        <td class="id"><?= $val['id'] ?></td>
                                        <td><?= $val['name'] ?></td>
                                        <td><?= $val['email'] ?></td>
                                        <td><?= $val['passwd'] ?></td>
                                        <td><?= $val['created_at'] ?></td>
                                        <?php if($val['id']!=='1'):?>
                                        <form method="POST" action="./admin_user_ctrl.php">
                                            <td>
                                                <input type="hidden" name="id" value="<?= $val['id'] ?>">
                                                <input type="hidden" name="type" value="delete">
                                                <input class="update_btn" type="submit" value="&#215;">
                                            </td>
                                        </form>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                        <div class="paging">
                            <?php if ($page > 1) : ?>
                            <a href="?page=<?php echo $page-1; ?>">&lt;&lt;</a>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>
                            <?php if ($page < $total_pages) : ?>
                            <a href="?page=<?php echo $page+1; ?>">&gt;&gt;</a>
                            <?php endif; ?>
                        </div>
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
