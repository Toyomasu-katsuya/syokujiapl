<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>管理メニュー</title>
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
        <form action="member_kanri.php" method="post" enctype="multipart/form-data">
            <a href="main_kanri.php" class="btn-square">メインへ</a>
            <fieldset>
                <legend><B>メニュー</B></legend>
                <a href="id_url.php" class="btn-square">チームIDの発行</a>
                <a href="member_kanri.php" class="btn-square">選手管理</a>
                <a href="" class="btn-square">スタッフ権限管理</a>
            </fieldset>
        </form>

        <?php
        $link = mysqli_connect('toyomasu.naviiiva.work', 'naviiiva_user', '!Samurai1234', 'toyomasu');
        // 接続状況をチェックします
        if (mysqli_connect_errno()) {
            die("データベースに接続できません:" . mysqli_connect_error() . "\n");
            
        }
        
        
        ?>
        
        
   
    </body>
</html>