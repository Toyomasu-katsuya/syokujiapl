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
        <form action="member_list.php" method="post" enctype="multipart/form-data">
            <a href="main_kanri.php" class="btn-square">メインへ</a>
            <fieldset>
                <legend><B>メンバー一覧</B></legend>
                <?php
                @session_start();
                $ID=$_SESSION['ID'];
                $Team=$_SESSION['TEAM'];
                date_default_timezone_set ('Asia/Tokyo');
                $today=date('Y-m-d');
                $link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
                // 接続状況をチェックします
                if (mysqli_connect_errno()) {
                    die("データベース接続に失敗しました:" . mysqli_connect_error() . "\n");
                    
                }
                //検索
                $SQL='select userid as メールアドレス, name as 名前, Authority as 権限 from members where team = "'.$Team.'"; ';
                if ($result = mysqli_query($link, $SQL)) {
                    if(mysqli_num_rows($result) > 0 ) { //データが存在していれば
                    foreach($result as $row){
                        $key = array_keys($row);
                        
                    }
                ?>
                <table border="0">
                    <tr>
                    <?php
                    for ($f = 0; $f < count($key); $f++){
                        echo "<th>".$key[$f]."</th>";
                        
                    }
                    ?>
                    </tr>
                    <?php
                    foreach($result as $row){
                    ?>
                    <tr>
                    <?php
                    for($j = 0; $j< count($row); $j++ ){
                    ?>
                    <td><?php echo $row[$key[$j]]?></td>
                    <?php } ?>
                    </tr>
                    <?php }
                    ?>
                    </table>
                    <?php
                    
                }else{
                    echo "記録がありません";
                    
                }
                }
                ?>

                </fieldset>
                
            </fieldset>
        </form>
        <a href="logout.php" >ログアウト</a>
   
    </body>
</html>