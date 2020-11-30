<?php
@session_start();
    // ログインしていなければ login.php に遷移
    if (!isset($_SESSION['ID'])) {
        header('Location: login.php');
        exit;
    }
?>

<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Mypage</title>
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
        <h3>ようこそ、<?php echo $_SESSION['NAME'];?>さん</h3>
        <script type="text/javascript">
            now=new Date();
            document.write("今日は",now.getMonth() +1,"月",now.getDate(),"日","です");
        </script>
        <form action="main.php" method="post">
            <fieldset>
                <legend><B>メニュー</B></legend>
                <!--入力欄-->
                <a href="syokuji.php" class="btn-square">食事登録</a>
                <a href="body_weight.php" class="btn-square">体重・体脂肪率登録</a><br>
                <a href="mailboxs.php" class="btn-square">メッセージボックス</a>
                <a href="kiroku.php" class="btn-square">記録確認</a>
            </fieldset>
        </form>
        <a href=SignUp.php> 個人設定 </a>
        <a href="logout.php" >ログアウト</a>
        
   
    </body>
</html>