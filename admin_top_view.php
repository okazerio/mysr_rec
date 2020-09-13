<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./styles_admin.css">
        <link href="https://fonts.googleapis.com/css2?family=Major+Mono+Display&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Heebo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <title>Mysore Records Admin Top</title>
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
                    <div class="weather">
                        <div class="weather_select">
                            <h3>Weather forcast <small>(based on 6:00 AM)</small></h3>
                            <form method="GET" enctype="multipart/form-data" action="./admin_top_ctrl.php" onchange="submit(this.form)">
                                <select name="area">
                                <?php foreach($areas as $key => $area): ?>
                                    <option value="<?= $key ?>"<?php if($area_id===$key){echo "selected";}?>>
                                        <?= $area ?>
                                    </option>
                                <?php endforeach; ?>
                                </select> 
                            </form>
                        </div>
                        <div class="weather_list">
                            <?php foreach($weather_forcast_list as $key => $val):?>
                                <?php $datetime = new DateTime(); ?>
                                <?php if($area_id==='1262321' || $area_id==='1277333'){
                                    $datetime->setTimestamp( $val['dt'] )->setTimeZone(new DateTimeZone('Asia/Kolkata')); // 日時 - 協定世界時 (UTC)を日本標準時 (JST)に変換
                                }else{
                                    $datetime->setTimestamp( $val['dt'] )->setTimeZone(new DateTimeZone('Asia/Tokyo')); // 日時 - 協定世界時 (UTC)を日本標準時 (JST)に変換
                                }
                                ?>
                                <?php if($time = $datetime->format('H:i') === '06:00'):?>
                                    <div class="weather_item">
                                        <ul>
                                            <li>
                                                <strong>
                                                    <?=$date =  $datetime->format('Y/m/d'); //　日付?>
                                                </strong>
                                            </li>
                                            <li>
                                                h : <?=$temp_max = $val['main']['temp_max']; // 最高気温?>&#8451;
                                            </li>
                                            <li>
                                                l : <?=$temp_min = $val['main']['temp_min']; // 最低気温?>&#8451; 
                                            </li>
                                            <li>
                                                hu : <?= $humidity = $val['main']['humidity']; // 湿度 ?>&#37;
                                            <li>
                                                <?php
                                                    $weather = $val['weather'][0]['main']; // 天気
                                                    $weather_icon = $val['weather'][0]['icon']; // 天気アイコン（公式のアイコンを使用）
                                                ?>
                                                <img src="https://openweathermap.org/img/wn/<?php echo $weather_icon; ?>@2x.png" alt="<?php echo $weather; ?>">
                                            </li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
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
