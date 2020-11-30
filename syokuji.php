<?php
 @session_start();
 $ID=$_SESSION['ID'];
 date_default_timezone_set ('Asia/Tokyo');
 $today=date('Y-m-d');
 $breakfast_menu="";
 $breakfast_file="";
 $breakfast_message="";
 $lunch_menu=""; 
 $lunch_file="";
 $lunch_message="";
 $dinner_menu=""; 
 $dinner_file="";
 $dinner_message="";
 
 $link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
 // 接続状況をチェックします
 if (mysqli_connect_errno()) {
     die("データベース接続に失敗しました:" . mysqli_connect_error() . "\n");
 }
 //デフォルト検索
 $SQL='select * from syokuji where ID = "'.$ID.'" AND today = "'.$today.'"; ';
 if ($result = mysqli_query($link, $SQL)) {
            foreach ($result as $row) {
                if(!empty($row['breakfast_menu'])){
                    $breakfast_menu=$row['breakfast_menu'];
                }
                if(!empty($row['breakfast_file'])){
                    $breakfast_file=$row['breakfast_file'];
                }
                if(!empty($row['breakfast_message'])){
                    $breakfast_file=$row['breakfast_message'];
                }
                if(!empty($row['lunch_menu'])){
                    $lunch_menu=$row['lunch_menu'];
                }
                if(!empty($row['lunch_file'])){
                    $lunch_file=$row['lunch_file'];
                }
                if(!empty($row['lunch_message'])){
                    $breakfast_file=$row['breakfast_message'];
                }
                if(!empty($row['dinner_file'])){
                    $dinner_menu=$row['dinner_menu'];
                }
                if(!empty($row['dinner_file'])){
                    $dinner_file=$row['dinner_file'];
                }
                if(!empty($row['dinner_message'])){
                    $breakfast_file=$row['breakfast_message'];
                }
            }
 }
?>

<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Mypage-食事登録-</title>
        <style type="text/css">
            body{
                width:500px; 
                float: none;
                text-align:center;
                margin:80px;
                margin-left: 30%;   
                background-color:#ccffff;
            }
            .btn-square {
                display: inline-block;
                width:150px;
                padding: 0.5em 1em;
                text-decoration: none;
                background: #668ad8;/*ボタン色*/
                color: #FFF;
                border-bottom: solid 4px #627295;
                border-radius: 3px;
                
            }
            .btn-square:active {
                /*ボタンを押したとき*/
                -webkit-transform: translateY(4px);
                transform: translateY(4px);/*下に動く*/
                border-bottom: none;/*線を消す*/
                
            }
            
        </style>
        
    </head>
    <body>
        <form action="syokuji.php" method="post" enctype="multipart/form-data">
            <a href="main.php" class="btn-square">メインへ</a>
            <fieldset>
                <legend><B>食事登録</B></legend>
                <input type="date" name="today" value="<?php echo $today; ?>"><br>
                <!--入力欄-->
                <fieldset>
                <legend><B>朝食</B></legend>
                メニュー:<input type="text" name="breakfast_menu" value="<?php echo $breakfast_menu; ?>"> <br>画像:<input type="file" name="breakfast_file"> <br>
                <a href='barcode.html'>バーコード読み取り</a> <br>
                一言メッセージ<input type="text" name="breakfast_message"  value="<?php echo $breakfast_message; ?>">
                </fieldset>
                <fieldset>
                <legend><B>昼食</B></legend>
                メニュー:<input type="text" name="lunch_menu" value="<?php echo $lunch_menu; ?>" > <br>画像:<input type="file" name="lunch_file"> <br>
                <a href='barcode.html'>バーコード読み取り</a> <br>
                一言メッセージ<input type="text"　name="lunch_message" value="<?php echo $lunch_message; ?>">
                </fieldset>
                <fieldset>
                <legend><B>夕食</B></legend>
                メニュー:<input type="text" name="dinner_menu" value="<?php echo $dinner_menu; ?>"> <br>画像:<input type="file" name="dinner_file"> <br>
                <a href='barcode.html'>バーコード読み取り</a> <br>
                一言メッセージ<input type="text" name="dinner_message" value="<?php echo $dinner_menu; ?>"> 
                </fieldset>
                <p><input type="submit" name="submit" value="登録"></p>
                
            </fieldset>
        </form>
        <a href="logout.php" >ログアウト</a>

        <?php
        if(@$_POST["submit"] == "登録"){
            $today=@$_POST["today"];
            $breakfast_menu=@$_POST["breakfast_menu"];
            $breakfast_file="";
            if ($_FILES['breakfast_file']['error']==0) {
                $breakfast_tmp_file=@$_FILES['breakfast_file']['tmp_name'];
                $breakfast_org_file=@$_FILES['breakfast_file']['name'];
                $breakfast_file=$ID."_". $today."_breakfast.".$ext = pathinfo($breakfast_org_file, PATHINFO_EXTENSION);
                rename($breakfast_tmp_file, $breakfast_file);
                chmod($breakfast_file, 0644);
            }
            $breakfast_message=@$_POST["breakfast_message"];
            $lunch_menu=@$_POST["lunch_menu"];
            $lunch_file="";
            if ($_FILES['lunch_file']['error']==0) {
                $lunch_tmp_file=@$_FILES['lunch_file']['tmp_name'];
                $lunch_org_file=@$_FILES['lunch_file']['name'];
                $lunch_file=$ID."_". $today."_lunch.".$ext = pathinfo($breakfast_org_file, PATHINFO_EXTENSION);
                rename($lunch_tmp_file, $lunch_file);
                chmod($lunch_file, 0644);
            }
            $lunch_message=@$_POST["lunch_message"];
            $dinner_menu=@$_POST["dinner_menu"]; 
            $dinner_file="";
            if ($_FILES['dinner_file']['error']==0) {
                $dinner_tmp_file=@$_FILES['dinner_file']['tmp_name'];
                $dinner_org_file=@$_FILES['dinner_file']['name'];
                $dinner_file=$ID."_". $today."_dinner.".$ext = pathinfo($breakfast_org_file, PATHINFO_EXTENSION);
                rename($dinner_tmp_file, $dinner_file);
                chmod($dinner_file, 0644);
            }
            $dinner_message=@$_POST["dinner_message"];
            $SQL='replace into syokuji (ID ,today ,breakfast_menu ,breakfast_file ,breakfast_message,lunch_menu ,lunch_file,lunch_message ,
            dinner_menu ,dinner_file,dinner_message) values("'.$ID.'" , "'.$today.'" , "'.$breakfast_menu.'","'.$breakfast_file.'" , "'.$breakfast_message.'" , "'.$lunch_menu.
            '","'.$lunch_file.'" , "'.$lunch_message.'","'.$dinner_menu.'" , "'.$dinner_file.'" , "'.$dinner_message.'");';
            
            if ($result = mysqli_query($link, $SQL)) {
                echo "<B>登録が完了しました</B>";
            }else{
                echo "登録に失敗しました";
            }
        }
        
        ?>
        
        
   
    </body>
</html>